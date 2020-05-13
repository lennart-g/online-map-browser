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
        <h1>WIP: DP2 Map Browser</h1>
        <form action="index.php" method="post">
            <label for="mapname">Map Name: </label>
            <input type="text" name="mapname">
            <input type="submit" value="Search">
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

$stmt = $db->prepare("SELECT * FROM maps where map_path like :mapname or message like :mapname or map_id in (select map_id from tags where tag_name like :mapname)");
$stmt->bindValue(':mapname', "%".$_POST["mapname"]."%", SQLITE3_TEXT);
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
