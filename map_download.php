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

#$file = "../pball/maps/italy.bsp";
$basepath = $_GET["path"];
$file = "./../pball/maps/".$_GET["path"];
$path_parts = pathinfo($file);
#echo $path_parts['extension']."<br>";
if (pathinfo($basepath)["dirname"]=="."){
$query_mapname = pathinfo($basepath)["filename"];
} else {
$query_mapname = pathinfo($basepath)["dirname"]."/".pathinfo($basepath)["filename"];
}
#echo $query_mapname;
if ($path_parts['extension']=="bsp"){
    if ($_GET["zipped"]==-1){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    } else if ($_GET["zipped"] == 1){
        $zip_file = '/var/www/html/filename.zip';

        $zip = new ZipArchive();
	        if ( $zip->open($zip_file, ZipArchive::CREATE) !== TRUE) {
	        exit("message");
	        }

        $zip->addFile($file, "pball/maps/".$query_mapname.".bsp");
        
        $db = new SQLite3("sqlite_mapdata.db");
        #echo $path_parts["filename"]."<br>";
        $stmt = $db->prepare("select * from media_files where file_id in (select file_id from requirements where map_id in (select map_id from maps where map_path = :mappath))");
    $stmt->bindValue(':mappath', $query_mapname, SQLITE3_TEXT);
    $mapdata = $stmt->execute();
    $img_extensions = array(".png", ".jpg", ".tga", ".pcx", ".wal");
    #$additional_paths = array();
    while ($row = $mapdata->fetchArray()) {
    #echo $row["path"]."<br><br>";
            $tex_ex = NULL;
            $subdir = NULL;
            if (startsWith($row["path"], "/")){
                $row["path"] = substr($row["path"],1);
            }
            if ($row["type"] === "texture"){
                foreach($img_extensions as $item) {
                    if (file_exists("../pball/textures/".$row["path"].$item)){
                        $tex_ex = "./../pball/textures/".$row["path"].$item;
                        $subdir = "pball/textures/".$row["path"].$item;
                    }
                }
            } else if ($row["type"] === "sky"){
                foreach($img_extensions as $item) {
                    if (file_exists("../pball/env/".$row["path"].$item)){
                        $tex_ex = "./../pball/env/".$row["path"].$item;
                        $subdir = "pball/env/".$row["path"].$item;
                    }
                }
            } else if ($row["type"] === "mapshot"){
                foreach($img_extensions as $item) {
                    if (file_exists("../pball/".$row["path"].$item)){
                        $tex_ex = "./../pball/".$row["path"].$item;
                        $subdir = "pball/".$row["path"].$item;
                    }
                }
            } else if ($row["type"] === "externalfile" && endsWith($row["path"], ".wav")){
            if (file_exists("../pball/sound/".$row["path"])){
                $tex_ex = "./../pball/sound/".$row["path"];
                $subdir = "pball/sound/".$row["path"];
                }
            } else if ($row["type"] === "externalfile" && !endsWith($row["path"], ".wav")){
                if (file_exists("../pball/".$row["path"])){
                $tex_ex = "./../pball/".$row["path"];
                $subdir = "pball/".$row["path"];
                }
            } else if ($row["type"] === "requiredfile"){
                if (file_exists("../pball/".$row["path"])){
                $tex_ex = "./../pball/".$row["path"];
                $subdir = "pball/".$row["path"];
                }
            } else if ($row["type"] === "linkedfile" && endsWith($row["path"], ".skp")){
                if (file_exists("../pball/".$row["path"])){
                $tex_ex = "./../pball/".$row["path"];
                $subdir = "pball/".$row["path"];
                }
            } else if ($row["type"] === "linkedfile" && !endsWith($row["path"], ".skp")){
                foreach($img_extensions as $item) {
                    if (file_exists("../pball/".$row["path"].$item)){
                        $tex_ex = "./../pball/".$row["path"].$item;
                        $subdir = "pball/".$row["path"].$item;
                    }
                }
            } 
            if (isset($tex_ex) && isset($subdir)){
                    $zip->addFile($tex_ex, $subdir);
            }

        $zip->close();

        header('Content-type: application/zip');
	        header('Content-Disposition: attachment; filename="'.pathinfo($basepath)["filename"].'.zip"');
	        header("Content-length: " . filesize($zip_file));
	        header("Pragma: no-cache");
	        header("Expires: 0");

        ob_clean();
	        flush();

        readfile($zip_file);

        unlink($zip_file);
    }
} else {
    echo "...";
}
