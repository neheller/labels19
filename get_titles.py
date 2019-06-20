from html.parser import HTMLParser
from pathlib import Path
import json


class MyParser(HTMLParser):

    def __init__(self):
        super(MyParser, self).__init__()
        self.entries = {}
        self.current_key = ""
        self.current_entry = -1
        self.inentry = False
        self.intitle_div = False
        self.intitle = False
        self.inauthor = False


    def handle_starttag(self, tag, attrs):
        if tag == "li":
            for attr in attrs:
                if attr[0] == "class" and "chapter-item" in attr[1].split():
                    self.inentry = True
                    self.current_entry = self.current_entry + 1
                    self.entries[self.current_key] = self.entries[self.current_key] + [{"title":"","authors":""}]
        if self.inentry and tag == "div":
            for attr in attrs:
                if attr[0] == "class" and "content-type-list__title" in attr[1].split():
                    self.intitle_div = True
                if attr[0] == "data-test" and "author-text" in attr[1].split():
                    self.inauthor = True
        if self.intitle_div and tag == "a":
            for attr in attrs:
                if attr[0] == "class" and "content-type-list__link" in attr[1].split():
                    self.intitle = True



    def handle_endtag(self, tag):
        if tag == "a":
            self.intitle = False
        if tag == "div":
            self.inauthor = False
            self.intitle_div = False
        if tag == "li":
            self.inentry = False

  
    def handle_data(self, data):
        if self.intitle:
            self.entries[self.current_key][self.current_entry]["title"] = data
        if self.inauthor:
            self.entries[self.current_key][self.current_entry]["authors"] = data


if __name__ == "__main__":
    parser = MyParser()
    htmlpath = Path(__file__).parent / "miccai_scrape"
    fpths = sorted([p for p in htmlpath.glob("*.html")])
    for fpth in fpths:
        parser.entries[fpth.stem] = []
        parser.current_key = fpth.stem
        parser.current_entry = -1
        with open(str(fpth)) as f:
            parser.feed(str(f.read()))

    with open("titles.json", "w") as f:
        f.write(json.dumps(parser.entries, indent=2))
