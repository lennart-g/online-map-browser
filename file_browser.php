<head>
    <title>DP2 File Browser</title>
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
    <?php
    $type_options = array("mapshot", "sky", "requiredfile", "texture", "sound", "externalfile", "linkedfile");
    $types = array();
    foreach ($type_options as $type){
        #echo $type." ".$_GET[$type]."<br>";
        if ($_GET[$type]==1){
            #echo "found <br>";
            array_push($types ,$type);
        }
    }
    #echo "types: ".print_r($types)."<br>";
    #echo "empty: ".count($types)."<br>";
    
if (count($types)==0){
    $query = "select * from media_files where (path like :mapname or type like :mapname)";
} else {
    if ($types[0]=="sound"){
        $query = "select * from media_files where (path like :mapname or type like :mapname) and (type = 'externalfile'";
    } else {
        $query = "select * from media_files where (path like :mapname or type like :mapname) and (type = '".$types[0]."'";
    }
    foreach ($types as $idx => $type){
        if ($idx < 1) continue;
        if ($type=="sound"){
            $query .= " or type = 'externalfile'";
        } else {
            $query .= " or type = '".$type."'";
        }
    }
    $query .= ")";
    if (in_array("sound", $types)){
        #echo "sound found";
        $query .=  " and path like '%.wav'";
    }
    
}
$prov = $_GET["provided"];
if (($prov==-1 || $prov==1)){
    #echo $prov." LALALALLA<br>";
    if ($_GET["provided"]==-1){
        $query .= " and provided = 0";
    } else {
        $query .= " and provided = 1";
    }
}
#echo $query;
    ?>
        <h1>DP2 File Browser</h1>
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
                    <th class="sorttable_alpha sort"><span>Path</span></th>
                    <th class="sorttable_alpha"><span>Type</span></th>
                    <th class="sorttable_alpha"><span>Is Provided</span></th>
                </tr>
            </thead>
            <tbody class="hoverthing">
                <?php

                
$db = new SQLite3("sqlite_mapdata.db");

$stmt = $db->prepare($query);

$stmt->bindValue(':mapname', "%".$_GET["mapname"]."%", SQLITE3_TEXT);
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
