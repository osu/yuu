<?php

require __DIR__."/../includes/database.php";

/**
 * Delete Files
 */

$time = time();
$time_log = date("d/m/Y - H:i", $time);

$q = $db->query("SELECT * FROM files WHERE delete_date < $time");
$q->setFetchMode(PDO::FETCH_ASSOC);
$files = $q->fetchAll();

foreach ($files as $file) {
    if(unlink(YUU_UPLOAD_FOLDER.$file["filename"])){
        file_put_contents(YUU_LOG_DIR, "[$time_log] Deleted ".$file['filename']."\n", FILE_APPEND);
    }
    $db->query("DELETE FROM files WHERE ID = ".$file["id"]);
}

if(count($files) > 0){
    print("[$time_log] Deleted ".count($files)." files");
}