import json


if __name__ == "__main__":
    with open("titles.json") as f:
        titles = json.loads(f.read())

    counts = {
        "2014": 0,
        "2015": 0,
        "2016": 0,
        "2017": 0,
        "2018": 0
    }
    tot_count = 0
    for key in titles:
        year = key[:4]
        counts[year] = counts[year] + len(titles[key])
        tot_count = tot_count + len(titles[key])

    print(json.dumps(counts, indent=2))
    print(tot_count)