<?php

function reorgArray($files) {
    set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
        if (0 === error_reporting()) {
            return false;
        }
        throw new Throwable($errstr, 0, $errno, $errfile, $errline);
    });
    $result = array();
    try {
        foreach ($files as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                $result[$key2][$key1] = $value2;
            }
        }
    } catch (\Throwable $th) {
        throw new Exception("Malformed upload form", 400);
    }
    return $result;
}

if(!isset($_FILES["upload"])){
    Response::error("No file uploaded", 400);
    exit;
}else{
    try {
        
        $files = reorgArray($_FILES["upload"]);

        $files_array = [];

        foreach ($files as $file) {
            $upload = new YuuUploader($file);

            $upload->chars = upload::chars;
            $upload->uploadURI = upload::uri;
            $upload->filenameLength = upload::namesize;
            $upload->filenameMaxRetry = upload::namesize;
            $upload->maxFilesize = upload::maxsize;
            $upload->extBlacklist = upload::unautorized_ext;
            $upload->uploadFolder = upload::uploadFolder;

            $upload->setDatabase(
                website::database["hostname"],
                website::database["port"],
                website::database["user"],
                website::database["passwd"],
                website::database["database"],
                website::database["charset"]
            );

            $data = $upload->uploadFile();

            array_push($files_array, $data);
        }

        Response::success($files_array);

    } catch (\Throwable $th) {
        Response::error($th->getMessage(), $th->getCode());
        exit;
    }
}