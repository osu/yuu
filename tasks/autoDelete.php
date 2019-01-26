<?php

require __DIR__."/../utils/configurations/websiteConf.php";
require __DIR__."/../utils/configurations/uploadConf.php";

$q = $db->query("SELECT * FROM uploads");
$q->setFetchMode(PDO::FETCH_ASSOC);
$files = $q->fetchAll();

foreach ($files as $file) {
    $delete_time = strtotime("+ " . upload::file_max_life . " hours", $file["upload_date"]);

    if($delete_time < time()){
        unlink(upload::uploadFolder.$file["filename"]);
        $db->query("DELETE FROM files WHERE ID = ".$file["ID"]);
    }
}