import json
import random

if __name__ == "__main__":
    with open("titles.json") as f:
        titles = json.loads(f.read())

    out = []

    counts = []
    papers = {
        "2014": [],
        "2015": [],
        "2016": [],
        "2017": [],
        "2018": []
    }
    for key in titles:
        year = key[:4]
        for paper in titles[key]:
            papers[year] = papers[year] + [paper]

    for year in papers:
        random.shuffle(papers[year])

    counts = [len(papers[yr]) for yr in papers]
    min_count = min(counts)
    for i in range(min_count):
        for j,year in enumerate(papers):
            out = out + [papers[year][i]]
            out[-1]["year"] = year

    for year in papers:
        for j in range(i, len(papers[year])):
            out = out + [papers[year][j]]
            out[-1]["year"] = year

    with open("order.json", "w") as f:
        f.write(json.dumps(out, indent=2))

