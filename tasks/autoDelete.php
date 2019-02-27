<?php

require __DIR__."/../utils/Loader.php";


$clear = new YuuClear();
$clear->upload_folder = __DIR__."/../uploads/";
$clear->setDatabase(
	Web::database["hostname"],
	Web::database["port"],
	Web::database["user"],
	Web::database["passwd"],
	Web::database["database"],
	Web::database["charset"]
);
$clear->clearFiles();