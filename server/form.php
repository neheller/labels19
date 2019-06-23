<?php
$ind = $_GET["paper"];
$order = json_decode(file_get_contents("../order.json"));
$paper = $order[$ind];
$scholar = "https://scholar.google.com/scholar?";
$getdata = array(
    "q"=>$paper->title . ' ' . $paper->authors
);
$query = $scholar . http_build_query($getdata);
$dataopts = array("custom");
$benchmarks = json_decode(file_get_contents("benchmarks.json"));
foreach ($benchmarks as $bmk) {
    array_push($dataopts, $bmk);
}
$keywords = json_decode(file_get_contents("keywords.json"));
// TODO get prev vals if they exist
// Otherwise set defaults
// http://scrape.pl/handler.php?
//citations=1
//datause=on
//whichdata=other
//whichdata=LiTS
//otherdata=asd
//didcite=none
//didpublish=withpub
$init = array(
    "citations" => 0
);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            p {
                margin: 15px;
            }
            div.ib {
                display: inline-block;
                width: auto;
            }
            select {
                margin-left: 20px;
            }
        </style>
	<meta name="robots" content="noindex,nofollow">
    </head>
    <body>
<?php
echo $ind;
echo <<<EOD
        <a href="$query">Scholar</a>
EOD;
?>
        <form action="/" method="get">
            <input type="text" name="ind" value=<?php echo $ind; ?> style="display:none;"/>
            <p><input id="citations" name="citations" type="number" maxlength="6" size="6" autocomplete="off"/> <label for="citations"> Citations</label></p>
            <p><input id="datause" name="datause" type="checkbox" autocomplete="off"/> <label for="datause"> This paper used visual data</label></p>
            <p>If no, submit now</p>
            <p><input id="openaccess" name="openaccess" type="checkbox" autocomplete="off"/> <label for="openaccess"> An open access version is available</label></p>
            <p>What data did they use?</p>
            <select id="whichdata" name="whichdata[]" autocomplete="off" multiple>
<?php
foreach ($dataopts as $benchmark) {
    echo <<<EOD
                <option value="$benchmark">$benchmark</option>
EOD;
}
?>
            </select><br>
            <p>If other<br>
            <input id="otherdata" name="otherdata" type="text" autocomplete="off"/> <label for="otherdata"> Other benchmarks (csv)</label></p>
            <select id="keywords" name="keywords[]" autocomplete="off" multiple>
<?php
foreach ($keywords as $keyword) {
    echo <<<EOD
                <option value="$keyword">$keyword</option>
EOD;
}
?>
            </select><br>
            <p>If other<br>
            <input id="otherkeywords" name="otherkeywords" type="text" autocomplete="off"/> <label for="otherkeywords"> Other keywords (csv)</label></p>

            <div class="ib">
            <p>If benchmarks</p>
            <p>Did they cite the benchmarks?<br>
                <input type="radio" name="didcite" id="all" value="all" autocomplete="off"> <label for="all">All</label><br>
                <input type="radio" name="didcite" id="some" value="some" autocomplete="off"> <label for="some">Some</label><br>
                <input type="radio" name="didcite" id="footnote" value="footnote" autocomplete="off"> <label for="footnote">Footnote</label><br>
                <input type="radio" name="didcite" id="invalid" value="invalid" autocomplete="off"> <label for="invalid">Invalid</label><br>
                <input type="radio" name="didcite" id="none" value="none" autocomplete="off"> <label for="none">None</label>
            </p>
            </div>
            <div class="ib">
            <p>If private</p>
            <p>Did they publish the data?<br>
                <input type="radio" name="didpublish" id="withpub" value="withpub" autocomplete="off"> <label for="withpub">With publication</label><br>
                <input type="radio" name="didpublish" id="promise" value="promise" autocomplete="off"> <label for="promise">Promised to</label><br>
                <input type="radio" name="didpublish" id="no" value="no" autocomplete="off"> <label for="no">No</label>
            </p>
            </div>

            <p><input id="code" name="code" type="checkbox" autocomplete="off"/> <label for="code"> They released their code</label></p>

            <p><input id="submit" type="submit" /></p>
        </form>
    </body>
</html>
