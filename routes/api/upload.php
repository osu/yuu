<?php

function reorgArray($files) {
    $result = array();
    foreach ($files as $key1 => $value1) {
		foreach ($value1 as $key2 => $value2) {
			$result[$key2][$key1] = $value2;
		}
	}
    return $result;
}

if(!isset($_FILES["upload"])){
    Response::error("No file uploaded", 400);
    exit;
}elseif(!isset($_POST["delete_time"]) || !in_array($_POST["delete_time"], ["24", "744", "8928"])){
	Response::error("Invalid delete date", 400);
    exit;
}else{
    try {
        
        $files = reorgArray($_FILES["upload"]);

        $files_array = [];

        foreach ($files as $file) {
			$upload = new YuuUpload($file);
			
			$upload->rand_length = Web::filename_size;
			$upload->retry_count = Web::max_filename_retry;
			$upload->chars = Web::chars;
			$upload->upload_folder = Web::uploadFolder;
		
			$upload->upload_size = Web::max_upload_size;
			$upload->ext_disabled = Web::unautorized_ext;
			$upload->delete_time = intval($_POST["delete_time"]);
		
			$upload->uri = Web::default_uri;
		
			$upload->clamav_host = Web::clamav["host"];
			$upload->clamav_port = Web::clamav["port"];

            $upload->setDatabase(
                Web::database["hostname"],
                Web::database["port"],
                Web::database["user"],
                Web::database["passwd"],
                Web::database["database"],
                Web::database["charset"]
            );

            $data = $upload->uploadFile();

            array_push($files_array, $data);
        }

        Response::success($files_array);

    } catch (\Throwable $th) {
		($th->getCode() == 0) ? $code = 500 : $code = $th->getCode();
        Response::error($th->getMessage(), $code);
        exit;
    }
}