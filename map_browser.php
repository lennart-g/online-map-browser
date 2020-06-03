<head>
    <title>DP2 Map Browser</title>
    <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
    <script>
        window.onload = function() {
            (document.getElementsByTagName( 'th' )[0]).click();
        };
    </script>
    <link rel="stylesheet" type="text/css" href="map_browser.css">
</head>
<body>
    <div id="wrapper">
        <h1>DP2 Map Browser</h1>
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
        <table class="sortable maplist_table" id="keywords" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="sorttable_alpha sort"><span>Map Name</span></th>
                    <th class="sorttable_alpha"><span>Map Path</span></th>
                    <th class="sorttable_alpha"><span>Message</span></th>
                </tr>
            </thead>
            <tbody class="hoverthing">
                <?php
$db = new SQLite3("sqlite_mapdata.db");
$categories = array();

if ($_GET["name"]==1){
    array_push($categories, "map_path like :mapname");
} 
if ($_GET["tags"]==1){
    array_push($categories, "map_id in (select map_id from tags where tag_name like :mapname)");
} 
if ($_GET["message"]==1){
    array_push($categories, "message like :mapname");
}
if (count($categories) == 0){
$query_string = "SELECT * FROM maps where map_path like :mapname or message like :mapname or map_id in (select map_id from tags where tag_name like :mapname)";
} else {
$query_string = "select * from maps where ".$categories[0];
    foreach ($categories as $idx => $category){
        if ($idx < 1) continue;
        $query_string.=" or ".$category;
    }
}

$stmt = $db->prepare($query_string);
$stmt->bindValue(':mapname', "%".$_GET["mapname"]."%", SQLITE3_TEXT);
$mapdata = $stmt->execute();

while ($row = $mapdata->fetchArray()) {
    $link = "map_detail.php?mapid=".$row["map_id"];
    echo "<tr>";
    echo "<td class='lalign'><a href='".$link."'><div style='height:100%;width:100%'>", $row["map_name"],"</div></a></td>";
    echo "<td><a href='".$link."'><div style='height:100%;width:100%'>", $row["map_path"],"</div></a></td>";
    echo "<td><a href='".$link."'><div style='height:100%;width:100%'>", $row["message"],"</div></a></td>";
    echo "</tr>";
}
                ?>
            </tbody>
        </table>
    </div> 
</body>
