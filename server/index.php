<?php
if (isset($_GET["ind"])) {
    file_put_contents("submissions/" . $_GET["ind"] . ".json", json_encode($_GET));
}
$benchmarks = json_decode(file_get_contents("benchmarks.json"));
if (isset($_GET["otherdata"]) && $_GET["otherdata"] != "") {
    $obks = explode(",", $_GET["otherdata"]);
    foreach ($obks as $obk) {
        if (!in_array(trim($obk), $benchmarks)) {
            array_push($benchmarks, trim($obk));
        }    
    }
    file_put_contents("benchmarks.json", json_encode($benchmarks));
}
$keywords = json_decode(file_get_contents("keywords.json"));
if (isset($_GET["otherkeywords"]) && $_GET["otherkeywords"] != "") {
    $obks = explode(",", $_GET["otherkeywords"]);
    foreach ($obks as $obk) {
        if (!in_array(trim($obk), $keywords)) {
            array_push($keywords, trim($obk));
        }
    }
    file_put_contents("keywords.json", json_encode($keywords));
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            li {
                padding: 10px;
            }
            li.done {
                color: grey;
            }
        </style>
	<meta name="robots" content="noindex,nofollow">
    </head>
    <body>
        <ol>
<?php
$order = json_decode(file_get_contents("../order.json"));
for ($i = 0; $i < count($order); $i++) {
    $done = "";
    if (file_exists("submissions/$i.json")) {
        $done = "done";
    }
    echo '<li class="' . $done . '">';
    echo '<span class="year">' . $order[$i]->year . '</span> ';
    echo '<a href="/form.php?paper=' . $i . '" class="title">' . $order[$i]->title . '</a> ';
    echo '<span class="authors">' . $order[$i]->authors . '</span> ';
    echo "</li>";
}
?>
        </ol>
    </body>
</html>
