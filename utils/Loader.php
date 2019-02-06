<?php

if(file_exists(__DIR__."/libs/autoload.php")){
	require __DIR__."/libs/autoload.php";
}

const __CLASSDIR__ = __DIR__."/classes/";
const __CONFIGDIR__ = __DIR__."/configs/";

$classes = scandir(__CLASSDIR__);
$configs = scandir(__CONFIGDIR__);

foreach ($classes as $file) {
	if(pathinfo($file, PATHINFO_EXTENSION) === "php"){
		require __CLASSDIR__.$file;
	}
}

foreach ($configs as $file) {
	if(pathinfo($file, PATHINFO_EXTENSION) === "php"){
		require __CONFIGDIR__.$file;
	}
}