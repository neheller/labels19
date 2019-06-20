import json
import scholarly


if __name__ == "__main__":
    with open("titles.json") as f:
        titles = json.loads(f.read())

    for key in titles:
        for i, paper in enumerate(titles[key]):
            query = paper["title"] + " " + " ".join(paper["authors"].split()[0:3])
            # try:
            result = next(scholarly.search_pubs_query(query))
            title = result.bib["title"]
            try:
                url = result.bib["url"]
            except KeyError:
                url = ""
            try:
                eprint = result.bib["eprint"]
            except KeyError:
                eprint = ""
            citedby = result.citedby
            id_scholarcitedby = result.id_scholarcitedby
            url_scholarbib = result.url_scholarbib
            print(paper["title"])
            print(result.bib["title"])
            # except StopIteration:
            #     print(paper["title"])
            #     print("NOT FOUND")
            #     title = ""
            #     url = ""
            #     eprint = ""
            #     citedby = ""
            #     id_scholarcitedby = ""
            #     url_scholarbib = ""

            titles[key][i]["scholar"] = {
                "title": title,
                "citedby": citedby,
                "id_scholarcitedby": id_scholarcitedby,
                "url_scholarbib": url_scholarbib,
                "eprint": eprint,
                "url": url
            }
            with open("scholar.json", "w") as f:
                f.write(json.dumps(titles, indent=2))
