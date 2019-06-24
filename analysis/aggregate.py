from pathlib import Path
import json
import csv


def _get(d,k):
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
    keys = [
        "ind", "datause", "preprint", "personal", "code", 
        "keywords", "whichdata", "didpublish", "didcite", "citations"
    ]
    outsheet = [keys]
    for sub in submissions.glob("*.json"):
        with open(str(sub)) as f:
            dat = json.loads(f.read())
        outsheet = outsheet + [[_get(dat, k) for k in keys]]

        outsheet[-1][keys.index("keywords")] = list(
            csv.reader(outsheet[-1][keys.index("keywords")])
        ) + list(
            csv.reader([_get(dat, "otherkeywords")])
        )
        outsheet[-1][keys.index("keywords")] = maybe_flat(
            outsheet[-1][keys.index("keywords")]
        )

        outsheet[-1][keys.index("whichdata")] = list(
            csv.reader(outsheet[-1][keys.index("whichdata")])
        ) + list(
            csv.reader([_get(dat, "otherdata")])
        )
        outsheet[-1][keys.index("whichdata")] = maybe_flat(
            outsheet[-1][keys.index("whichdata")]
        )

    outsheet = sorted(outsheet, key=lambda x: (-1 if (x[0] == "ind") else int(x[0])))

    with open("aggregated.csv", "w") as f:
        writer = csv.writer(f)
        [writer.writerow(r) for r in outsheet]
