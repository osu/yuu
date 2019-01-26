<?php

const CLASSES_FOLDER = __DIR__."/classes/";
const CONFIG_FOLDER = __DIR__."/configurations/";
const AUTOLOAD_FILE = __DIR__."/../libs/autoload.php";

require AUTOLOAD_FILE;

$classes_files = scandir(CLASSES_FOLDER);
$config_files = scandir(CONFIG_FOLDER);

foreach ($classes_files as $file) {
    $ext = pathinfo(CLASSES_FOLDER.$file, PATHINFO_EXTENSION);

    if($ext === "php"){
        require CLASSES_FOLDER.$file;
    }
}

foreach ($config_files as $file) {
    $ext = pathinfo(CONFIG_FOLDER.$file, PATHINFO_EXTENSION);

    if($ext === "php"){
        require CONFIG_FOLDER.$file;
    }
}