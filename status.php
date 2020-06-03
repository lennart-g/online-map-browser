<head>
    <link rel="stylesheet" type="text/css" href="map_browser.css">
            <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>

    <title>
DP2 Database Status
    </title>
</head
<body>
<h1><a href="map_download.php">Stats of the DP2 Map database</a></h1>
<?php
$db = new SQLite3("sqlite_mapdata.db");

$mapdata = $db->query("SELECT count(*) FROM maps");
$num_maps = $mapdata->fetchArray()[0];
echo "<h2><a href='map_browser.php'><u>Number of maps:".$num_maps."</u></a><br/>";
$tagdata = $db->query("SELECT count(*) FROM tags");
$num_tags = $tagdata->fetchArray()[0];
echo "Number of tags:".$num_tags."</h2>";

$msdata = $db->query("SELECT count(*) FROM media_files where type='mapshot'");
$num_ms = $msdata->fetchArray()[0];
$msdata = $db->query("SELECT count(*) FROM media_files where type='mapshot' and provided=1");
$num_msp = $msdata->fetchArray()[0];
$msdata = $db->query("SELECT count(*) FROM media_files where type='mapshot' and provided=0");
$num_msm = $msdata->fetchArray()[0];

echo "<h2><a href='file_browser.php?mapshot=1'><u> Expected number of Mapshots (should match the number of maps): ".$num_ms."</u></a><br/>";
echo "<u><a href='file_browser.php?mapshot=1&provided=1'>Provided: ".$num_msp."</u></a></br>";
echo "<u><a href='file_browser.php?mapshot=1&provided=-1'>Missing: ".$num_msm."</u></a></br>";
$percentage = (int)($num_msp * 10000 / $num_ms + .5);
$percentage = $percentage/100;
echo "Percentage: ".$percentage."%</h2>";


$msdata = $db->query("SELECT count(*) FROM media_files where type='sky'");
$num_ms = $msdata->fetchArray()[0];
$msdata = $db->query("SELECT count(*) FROM media_files where type='sky' and provided=1");
$num_msp = $msdata->fetchArray()[0];
$msdata = $db->query("SELECT count(*) FROM media_files where type='sky' and provided=0");
$num_msm = $msdata->fetchArray()[0];

echo "<h2> <a href='file_browser.php?sky=1'><u>Expected number of env files: ".$num_ms." (sky files: ".($num_ms/6).")</u></a><br/>";
echo "<a href='file_browser.php?sky=1&provided=1'><u>Provided: ".$num_msp."</u></a></br>";
echo "<a href='file_browser.php?sky=1&provided=-1'><u>Missing: ".$num_msm."</u></a></br>";
$percentage = (int)($num_msp * 10000 / $num_ms + .5);
$percentage = $percentage/100;
echo "Percentage: ".$percentage."%</h2>";


$msdata = $db->query("SELECT count(*) FROM media_files where type='requiredfile'");
$num_ms = $msdata->fetchArray()[0];
$msdata = $db->query("SELECT count(*) FROM media_files where type='requiredfile' and provided=1");
$num_msp = $msdata->fetchArray()[0];
$msdata = $db->query("SELECT count(*) FROM media_files where type='requiredfile' and provided=0");
$num_msm = $msdata->fetchArray()[0];

echo "<h2> <a href='file_browser.php?requirefile=1'><u>Expected number of required files in worldspawn: ".$num_ms."</u></a><br/>";
echo "<a href='file_browser.php?requirefile=1&provided=1'><u>Provided: ".$num_msp."</u></a></br>";
echo "<a href='file_browser.php?requirefile=1&provided=-1'><u>Missing: ".$num_msm."</u></a></br>";
$percentage = (int)($num_msp * 10000 / $num_ms + .5);
$percentage = $percentage/100;
echo "Percentage: ".$percentage."%</h2>";


$msdata = $db->query("SELECT count(*) FROM media_files where type='texture'");
$num_tex = $msdata->fetchArray()[0];
$msdata = $db->query("SELECT count(*) FROM media_files where type='texture' and provided=1");
$num_texp = $msdata->fetchArray()[0];
$msdata = $db->query("SELECT count(*) FROM media_files where type='texture' and provided=0");
$num_texm = $msdata->fetchArray()[0];

echo "<h2> <a href='file_browser.php?texture=1'><u>Expected number of textures: ".$num_tex."</u></a><br/>";
echo "<a href='file_browser.php?texture=1&provided=1'><u>Provided: ".$num_texp."</u></a></br>";
echo "<a href='file_browser.php?texture=1&provided=-1'><u>Missing: ".$num_texm."</u></a></br>";
$percentage = (int)($num_texp * 10000 / $num_tex + .5);
$percentage = $percentage/100;
echo "Percentage: ".$percentage."%</h2>";

$msdata = $db->query("SELECT count(*) FROM media_files where type='externalfile' and path like '%.wav'");
$num_ms = $msdata->fetchArray()[0];
$msdata = $db->query("SELECT count(*) FROM media_files where type='externalfile' and provided=1 and path like '%.wav'");
$num_msp = $msdata->fetchArray()[0];
$msdata = $db->query("SELECT count(*) FROM media_files where type='externalfile' and provided=0 and path like '%.wav'");
$num_msm = $msdata->fetchArray()[0];

echo "<h2> <a href='file_browser.php?sound=1'><u>Expected number of sound files used by entities: ".$num_ms."</u></a><br/>";
echo "<a href='file_browser.php?sound=1&provided=1'><u>Provided: ".$num_msp."</u></a></br>";
echo "<a href='file_browser.php?sound=1&provided=-1'><u>Missing: ".$num_msm."</u></a></br>";
$percentage = (int)($num_msp * 10000 / $num_ms + .5);
$percentage = $percentage/100;
echo "Percentage: ".$percentage."%</h2>";


$msdata = $db->query("SELECT count(*) FROM media_files where type='externalfile' and not path like '%.wav'");
$num_ms = $msdata->fetchArray()[0];
$msdata = $db->query("SELECT count(*) FROM media_files where type='externalfile' and provided=1 and not path like '%.wav'");
$num_msp = $msdata->fetchArray()[0];
$msdata = $db->query("SELECT count(*) FROM media_files where type='externalfile' and provided=0 and not path like '%.wav'");
$num_msm = $msdata->fetchArray()[0];

echo "<h2> <a href='file_browser.php?externalfile=1'><u>Expected number of models and explicitly linked skins: ".$num_ms."</u></a><br/>";
echo "<a href='file_browser.php?externalfile=1&provided=1'><u>Provided: ".$num_msp."</u></a></br>";
echo "<a href='file_browser.php?externalfile=1&provided=-1'><u>Missing: ".$num_msm."</u></a></br>";
$percentage = (int)($num_msp * 10000 / $num_ms + .5);
$percentage = $percentage/100;
echo "Percentage: ".$percentage."%</h2>";


$msdata = $db->query("SELECT count(*) FROM media_files where type='linkedfile'");
$num_ms = $msdata->fetchArray()[0];
$msdata = $db->query("SELECT count(*) FROM media_files where type='linkedfile' and provided=1");
$num_msp = $msdata->fetchArray()[0];
$msdata = $db->query("SELECT count(*) FROM media_files where type='linkedfile' and provided=0");
$num_msm = $msdata->fetchArray()[0];

echo "<h2><u> <a href='file_browser.php?linkedfile=1'>Expected number of model-associated files (skins linked by model file and .skp for .skm model) : ".$num_ms."</u></a><br/>";
echo "<a href='file_browser.php?linkedfile=1&provided=1'><u>Provided: ".$num_msp."</u></a></br>";
echo "<a href='file_browser.php?linkedfile=1&provided=-1'><u>Missing: ".$num_msm."</u></a></br>";
$percentage = (int)($num_msp * 10000 / $num_ms + .5);
$percentage = $percentage/100;
echo "Percentage: ".$percentage."%</h2>";
?>
</body>
