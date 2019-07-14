from pathlib import Path
import json
import csv


def _get(d,k,ord):
    if k == "year":
        return ord[int(d["ind"])]["year"]
    try:
        return d[k]
    except KeyError:
        return ""


def maybe_flat(l):
    flat_list = []
    for sublist in l:
        for item in sublist:
            flat_list.append(item)
    return flat_list


if __name__ == "__main__":
    submissions = Path(__file__).parent.parent / "server/submissions"

    with open(str(Path(__file__).parent.parent / "order.json")) as f:
        order = json.loads(f.read())

    keys = [
        "ind", "year", "datause", "preprint", "personal", "code", 
        "keywords", "whichdata", "didpublish", "didcite", "citations"
    ]
    outsheet = [keys]
    outjson = []
    for sub in submissions.glob("*.json"):
        with open(str(sub)) as f:
            dat = json.loads(f.read())
        outsheet = outsheet + [[_get(dat, k, order) for k in keys]]
        outjson = outjson + [{}]
        for i, k in enumerate(keys):
            outjson[-1][k] = _get(dat, k, order)


        outsheet[-1][keys.index("keywords")] = list(
            csv.reader(outsheet[-1][keys.index("keywords")])
        ) + list(
            csv.reader([_get(dat, "otherkeywords", order)])
        )
        outsheet[-1][keys.index("keywords")] = maybe_flat(
            outsheet[-1][keys.index("keywords")]
        )
        outjson[-1]["keywords"] = outsheet[-1][keys.index("keywords")]

        outsheet[-1][keys.index("whichdata")] = list(
            csv.reader(outsheet[-1][keys.index("whichdata")])
        ) + list(
            csv.reader([_get(dat, "otherdata", order)])
        )
        outsheet[-1][keys.index("whichdata")] = maybe_flat(
            outsheet[-1][keys.index("whichdata")]
        )
        outjson[-1]["whichdata"] = outsheet[-1][keys.index("whichdata")]


    outsheet = sorted(outsheet, key=lambda x: (-1 if (x[0] == "ind") else int(x[0])))
    outjson = sorted(outjson, key=lambda x: int(x["ind"]))

    with open("aggregated.csv", "w") as f:
        writer = csv.writer(f)
        [writer.writerow(r) for r in outsheet]

    with open("aggregated.json", "w") as f:
        f.write(json.dumps(outjson, indent=2))