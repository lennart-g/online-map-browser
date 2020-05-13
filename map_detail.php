<head>
    <link rel="stylesheet" type="text/css" href="map_browser.css">
    <title>
        <?php
$db = new SQLite3("sqlite_mapdata.db");

$mapdata = $db->query("SELECT * FROM maps where map_id = ".$_GET["mapid"].";");
while ($row = $mapdata->fetchArray()) {
    echo $row["map_name"];
}
        ?>
    </title>
</head>
<body>
    <div id="wrapper">
        <form action="index.php" method="post">
            <label for="mapname">Map Name: </label><input type="text" name="mapname">
            <input type="submit" value="Search">
        </form>
        <div class="head">
            <h1>
            <?php
while ($row = $mapdata->fetchArray()) {
    echo $row["map_name"]."</h1>";
    echo "<a href='../maps/".$row["map_path"].".bsp'>";
    echo '<img src="https://image.flaticon.com/icons/svg/0/532.svg" alt="Mapshot" style="max-width:40px; max-height:40px; width: auto; height: auto">';
    echo "</a>";
}
            ?>
        </div>

        <div id="wrapper">
            <div class="row">
                <div class="column">
                    <?php
while ($row = $mapdata->fetchArray()) {
    echo '<img src="../mapshots/'.$row["map_path"].'.jpg" alt="Mapshot" style="width:100%" class="mapimage">';
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
$mapdata = $db->query("SELECT * FROM tags where map_id = ".$_GET["mapid"].";");
while ($row=$mapdata->fetchArray()) {
    echo $row["tag_name"]." ";
}
            ?>
        </h2>
    </div>
</body>
