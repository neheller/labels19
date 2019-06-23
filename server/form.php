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

if (file_exists("submissions/" . $ind . ".json")) {
    $init = json_decode(file_get_contents("submissions/" . $ind . ".json"));
}
else {
    $init = array(
        "citations" => "",
        "datause" => false,
        "preprint" => false,
        "personal" => false,
        "whichdata" => [""],
        "otherdata" => "",
        "keywords" => [""],
        "otherkeywords" => "",
        "didcite" => "",
        "didpublish" => "",
        "code" => false
    );
}
$init->datause = isset($init->datause) && $init->datause == "on";
$init->preprint = isset($init->preprint) && $init->preprint == "on";
$init->personal = isset($init->personal) && $init->personal == "on";
$init->code = isset($init->code) && $init->code == "on";

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
echo $ind+1;
echo <<<EOD
        <a href="$query">Scholar</a>
EOD;
?>
        <form action="/" method="get">
            <input type="text" name="ind" value=<?php echo $ind; ?> style="display:none;" />
            <p><input id="citations" name="citations" type="number" maxlength="6" size="6" autocomplete="off" value="<?php echo $init->citations; ?>"/> <label for="citations"> Citations</label></p>
            <p><input id="datause" name="datause" type="checkbox" autocomplete="off" <?php if ($init->datause) {echo "checked";} ?>/> <label for="datause"> This paper used visual data</label></p>
            <p>If no, submit now</p>
            <p><input id="preprint" name="preprint" type="checkbox" autocomplete="off" <?php if ($init->preprint) {echo "checked";} ?>/> <label for="preprint"> A preprint is available</label></p>
            <p><input id="personal" name="personal" type="checkbox" autocomplete="off" <?php if ($init->personal) {echo "checked";} ?>/> <label for="personal"> A personal version is available</label></p>
            <p>What data did they use?</p>
            <select id="whichdata" name="whichdata[]" autocomplete="off" multiple>
<?php
foreach ($dataopts as $benchmark) {
    if (in_array($benchmark, $init->whichdata)) {
        $selected = 'selected="selected"';
    }
    else {
        $selected = "";
    }
    echo <<<EOD
                <option value="$benchmark" $selected>$benchmark</option>
EOD;
}
?>
            </select><br>
            <p>If other<br>
            <input id="otherdata" name="otherdata" type="text" autocomplete="off" value="<?php echo $init->otherdata;?>"/> <label for="otherdata"> Other benchmarks (csv)</label></p>
            <select id="keywords" name="keywords[]" autocomplete="off" multiple>
<?php
foreach ($keywords as $keyword) {
    if (in_array($keyword, $init->keywords)) {
        $seld = 'selected="selected"';
    }
    else {
        $seld = "";
    }
    echo <<<EOD
                <option value="$keyword" $seld>$keyword</option>
EOD;
}
?>
            </select><br>
            <p>If other<br>
            <input id="otherkeywords" name="otherkeywords" type="text" autocomplete="off" value="<?php echo $init->otherkeywords;?>"/> <label for="otherkeywords"> Other keywords (csv)</label></p>

            <div class="ib">
            <p>If benchmarks</p>
            <p>Did they cite the benchmarks?<br>
                <input type="radio" name="didcite" id="all" value="all" autocomplete="off" <?php if ($init->didcite == "all") {echo "checked";} ?>> <label for="all">All</label><br>
                <input type="radio" name="didcite" id="some" value="some" autocomplete="off" <?php if ($init->didcite == "some") {echo "checked";} ?>> <label for="some">Some</label><br>
                <input type="radio" name="didcite" id="footnote" value="footnote" autocomplete="off" <?php if ($init->didcite == "footnote") {echo "checked";} ?>> <label for="footnote">Footnote</label><br>
                <input type="radio" name="didcite" id="invalid" value="invalid" autocomplete="off" <?php if ($init->didcite == "invalid") {echo "checked";} ?>> <label for="invalid">Invalid</label><br>
                <input type="radio" name="didcite" id="none" value="none" autocomplete="off" <?php if ($init->didcite == "none") {echo "checked";} ?>> <label for="none">None</label>
            </p>
            </div>
            <div class="ib">
            <p>If private</p>
            <p>Did they publish the data?<br>
                <input type="radio" name="didpublish" id="withpub" value="withpub" autocomplete="off" <?php if ($init->didpublish == "withpub") {echo "checked";} ?>> <label for="withpub">With publication</label><br>
                <input type="radio" name="didpublish" id="promise" value="promise" autocomplete="off" <?php if ($init->didpublish == "promise") {echo "checked";} ?>> <label for="promise">Promised to</label><br>
                <input type="radio" name="didpublish" id="no" value="no" autocomplete="off" <?php if ($init->didpublish == "no") {echo "checked";} ?>> <label for="no">No</label>
            </p>
            </div>

            <p><input id="code" name="code" type="checkbox" autocomplete="off" <?php if ($init->code) {echo "checked";} ?>/> <label for="code"> They released their code</label></p>

            <p><input id="submit" type="submit" /></p>
        </form>
    </body>
</html>
