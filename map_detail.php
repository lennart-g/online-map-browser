<head>
    <link rel="stylesheet" type="text/css" href="map_browser.css">
            <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
    <script>
        window.onload = function() {
            (document.getElementsByTagName( 'th' )[0]).click();
        };
    </script>
    <title>
        <?php
$db = new SQLite3("sqlite_mapdata.db");

$stmt = $db->prepare("SELECT * FROM maps where map_id = :mapid");
$stmt->bindValue(':mapid', $_GET["mapid"], SQLITE3_TEXT);
$mapdata = $stmt->execute();

#$mapdata = $db->query("SELECT * FROM maps where map_id = ".$_GET["mapid"].";");
while ($row = $mapdata->fetchArray()) {
    echo $row["map_name"];
}
        ?>
    </title>
</head>
<body>
    <div id="wrapper">
        <form action="map_browser.php" method="get">
            <label for="mapname">Map Name: </label>
            <input type="text" name="mapname">
            <input type="submit" value="Search Map">
            <br>
            <label><input type="checkbox" name="name" value="1" checked="true"/> Search map names</label>
            <label><input type="checkbox" name="tags" value="1" checked="true"/> Search tags</label>
            <label><input type="checkbox" name="message" value="1" checked="true"/> Search message</label>
        </form>
        <form action="file_browser.php" method="get">
            <label for="mapname">File Name: </label>
            <input type="text" name="mapname">
            <input type="submit" value="Search File">
            <br>
            <label><input type="radio" id="option2" name="provided" value="1"/> Only provided files</label>
            <label><input type="radio" id="option3"name="provided" value="-1"/> Only missing ones</label>
            <label><input type="radio" id="option4" name="provided" value="0" checked="true"/> Both</label>
            <br>
            <label><input type="checkbox" name="mapshot" value="1" checked="true"/> Mapshots</label>
            <label><input type="checkbox" name="sky" value="1" checked="true"/> Skies</label>
            <label><input type="checkbox" name="requiredfile" value="1" checked="true"/> Requiredfiles</label>
            <label><input type="checkbox" name="texture" value="1" checked="true"/> Textures</label>
            <label><input type="checkbox" name="externalfile" value="1" checked="true"/> Externalfiles</label>
            <label><input type="checkbox" name="linkedfile" value="1" checked="true"/> Linkedfiles</label>
        </form>
        <div class="head">
            <h1>
            <?php
while ($row = $mapdata->fetchArray()) {
    echo $row["map_name"]."</h1>";
    #echo "<a href='map_download.php?path=".$row["map_path"].".bsp'>";
#    echo "<a href='../pball/maps/".$row["map_path"].".bsp'>";
echo '<input type="image" src="https://image.flaticon.com/icons/svg/0/532.svg" id="link" style="max-width:40px; max-height:40px; width: auto; height: auto"/>';
echo '<label><input type="checkbox" id="option1" checked="true"/> Include files</label>';
echo '<script>';
echo 'document.getElementById("link").onclick = function() {';
echo '    if( document.getElementById("option1").checked) {';
echo '        location.href = "map_download.php?zipped=1&path='.$row["map_path"].'.bsp";';
echo '    }';
echo '    else {';
echo '        location.href = "map_download.php?zipped=-1&path='.$row["map_path"].'.bsp";';
echo '    }';
echo '};';
echo '</script>';
#    echo '<img src="https://image.flaticon.com/icons/svg/0/532.svg" alt="Mapshot" style="max-width:40px; max-height:40px; width: auto; height: auto">';
    #echo "</a>";
}
            ?>
        </div>

        <div id="wrapper">
            <div class="row">
                <div class="column">
                    <?php
while ($row = $mapdata->fetchArray()) {
    echo '<img src="../pball/pics/mapshots/'.$row["map_path"].'.jpg" alt="Mapshot" style="width:100%" class="mapimage">';
}
                    ?>
                </div>
                <div class="column">
                    <?php
while ($row = $mapdata->fetchArray()) {
    echo '<img src="../topshots/'.$row["map_path"].'.jpg" alt="Topdown" style="width:100%" class="mapimage">';
}
                    ?>
                </div>
            </div>
        </div>

        <table id="keywords" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th><span>Map ID</span></th>
                    <th><span>Map Name</span></th>
                    <th><span>Map Path</span></th>
                    <th><span>Message</span></th>
                </tr>
            </thead>
            <tbody>
                <?php
while ($row = $mapdata->fetchArray()) {
    echo "<tr>";
    echo "<td class='lalign'>", $row["map_id"] ,"</a></td>";
    echo "<td>", $row["map_name"],"</td>";
    echo "<td>", $row["map_path"],"</td>";
    echo "<td>", $row["message"],"</td>";
    echo "</tr>";
}
                ?>
            </tbody>
        </table>
        <h2>
            Tags:
            <?php
$tagdata = $db->query("SELECT * FROM tags where map_id = ".$_GET["mapid"].";");
while ($row=$tagdata->fetchArray()) {
    echo $row["tag_name"]." ";
}
            ?>
        </h2>
        
                <h2>
            File Requirements:
            </h2>
        <table class="sortable maplist_table" id="keywords" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="sorttable_alpha sort"><span>File Path</span></th>
                    <th class="sorttable_alpha"><span>Type</span></th>
                    <th class="sorttable_alpha"><span>Provided</span></th>
                </tr>
            </thead>
            <tbody class="hoverthing">
                <?php
$map_path = $mapdata->fetchArray()["map_path"];

$stmt = $db->prepare("select * from media_files where file_id in (select file_id from requirements where map_id in (select map_id from maps where map_path = :mappath))");
$stmt->bindValue(':mappath', $map_path, SQLITE3_TEXT);
$mapdata = $stmt->execute();

while ($row = $mapdata->fetchArray()) {
    $link = "file_detail.php?mapid=".$row["file_id"];
    echo "<tr>";
    echo "<td class='lalign'><a href='".$link."'><div style='height:100%;width:100%'>", $row["path"],"</div></a></td>";
    echo "<td><a href='".$link."'><div style='height:100%;width:100%'>", $row["type"],"</div></a></td>";
    echo "<td><a href='".$link."'><div style='height:100%;width:100%'>", $row["provided"],"</div></a></td>";
    echo "</tr>";
}
                ?>
            </tbody>
        </table>

    </div>
</body>
