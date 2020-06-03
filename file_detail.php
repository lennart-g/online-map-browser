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
function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}
        
$db = new SQLite3("sqlite_mapdata.db");

$stmt = $db->prepare("SELECT * FROM media_files where file_id = :fileid");
$stmt->bindValue(':fileid', $_GET["mapid"], SQLITE3_TEXT);
$mapdata = $stmt->execute();

$img_extensions = array(".png", ".jpg", ".tga", ".pcx", ".wal");

while ($row = $mapdata->fetchArray()) {
    echo $row["path"];
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
    </div>
    <div id="wrapper">
        <div class="head">
            <h1>
            <?php
while ($row = $mapdata->fetchArray()) {
    echo $row["path"]."</h1>";
    $tex_ex = "";
    if ($row["type"] === "texture"){
        foreach($img_extensions as $item) {
            if (file_exists("../pball/textures/".$row["path"].$item)){
                $tex_ex = "./../pball/textures/".$row["path"].$item;
            }
        }
    } else if ($row["type"] === "sky"){
        foreach($img_extensions as $item) {
            if (file_exists("../pball/env/".$row["path"].$item)){
                $tex_ex = "./../pball/env/".$row["path"].$item;
            }
        }
    } else if ($row["type"] === "mapshot"){
        foreach($img_extensions as $item) {
            if (file_exists("../pball/".$row["path"].$item)){
                $tex_ex = "./../pball/".$row["path"].$item;
            }
        }
    } else if ($row["type"] === "externalfile" && endsWith($row["path"], ".wav")){
        $tex_ex = "./../pball/sound/".$row["path"];
    } else if ($row["type"] === "externalfile" && !endsWith($row["path"], ".wav")){
        $tex_ex = "./../pball/".$row["path"];
    } else if ($row["type"] === "requiredfile"){
        $tex_ex = "./../pball/".$row["path"];
    } else if ($row["type"] === "linkedfile" && endsWith($row["path"], ".skp")){
        $tex_ex = "./../pball/".$row["path"];
    } else if ($row["type"] === "linkedfile" && !endsWith($row["path"], ".skp")){
        foreach($img_extensions as $item) {
            if (file_exists("../pball/".$row["path"].$item)){
                $tex_ex = "./../pball/".$row["path"].$item;
            }
        }
    } 

    
    if ($row["provided"] === 1){
        echo "<a href='".$tex_ex."'>";        
        

        echo '<img src="https://image.flaticon.com/icons/svg/0/532.svg" alt="Mapshot" style="max-width:40px; max-height:40px; width: auto; height: auto">';
        echo "</a>";
    }
}
            ?>
        </div>


        <div id="wrapper">
            <div class="row">
                <div class="column">
                    <?php

                    
                    $row = $mapdata->fetchArray();
                    if (endsWith($tex_ex, ".png") || endsWith($tex_ex, ".jpg")){
                        echo '<img src="'.$tex_ex.'" alt="Texture not found" style="width:100%" class="mapimage">';
                    }
                    ?>
                    

                </div>
                <div class="column">
                    
                </div>
            </div>
        </div>

        <table id="keywords" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th><span>File ID</span></th>
                    <th><span>Path</span></th>
                    <th><span>Type</span></th>
                    <th><span>Provided</span></th>
                </tr>
            </thead>
            <tbody>
                <?php

    echo "<tr>";
    echo "<td class='lalign'>", $row["file_id"] ,"</a></td>";
    echo "<td>", $row["path"],"</td>";
    echo "<td>", $row["type"],"</td>";
    echo "<td>", $row["provided"],"</td>";
    echo "</tr>";

                ?>
            </tbody>
        </table>
        <h2>
            Required by:
            </h2>
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

$file_path = $row["path"];

$stmt = $db->prepare("SELECT * FROM maps where map_id in (select map_id from requirements where file_id in (select file_id from media_files where path = :filepath))");
$stmt->bindValue(':filepath', $file_path, SQLITE3_TEXT);
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
