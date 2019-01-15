<?php

require __DIR__."/database.php";
require __DIR__."/../classes/UploadFileClass.php";

/**
 * Generate a unique name
 * 
 * @param UploadedFile $file File who was uploaded
 * 
 * @return string $name New name of file
 */

function random_name($file)
{
    global $db;

    $charset = YUU_UPLOAD_CHARSET;
    $retry = YUU_UPLOAD_NAME_MAX_RETRY;
    $string_length = YUU_UPLOAD_NAME_LENGTH;

    $ext = pathinfo($file->name, PATHINFO_EXTENSION);

    do {
        
        if($retry-- === 0){
            throw new Exception("Unable to find a unique name");
        }

        $name = "";
        
        for ($i=0; $i < $string_length; $i++) { 
            $name .= $charset[rand(0, strlen($charset)-1)];
        }

        if (isset($ext) && $ext !== "") {
            $name .= ".".$ext;
        }
        
        $q = $db -> prepare("SELECT COUNT(*) FROM files WHERE filename = :name");
        $q -> bindParam(":name", $name);
        $q -> execute();
        $res = $q -> fetchColumn();

    } while ($res > 0);

    return $name;

}

/**
 * Reorganise file array
 * 
 * @param array $_FILES Array of files
 * 
 * @return array array of files reorganized
 * 
 */
function reorgArray($files)
{
    $result = array();
    foreach ($files as $key1 => $value1) {
        try {
            foreach ($value1 as $key2 => $value2) {
                $result[$key2][$key1] = $value2;
            }
        } catch (\Throwable $th) {
            throw new Exception("Invalid input form");
        }
    }
    return $result;
}


/**
 * Reorganise file array
 * 
 * @param array $_FILES Array of files
 * 
 * @return array array of files reorganized
 * 
 */
function remakeFile($files)
{
    $result = array();
    $files = reorgArray($files);
    foreach ($files as $file) {
        $f = new UploadFile();
        $f->name = $file['name'];
        $f->mime = $file['type'];
        $f->size = $file['size'];
        $f->tempfile = $file['tmp_name'];
        $f->errorCode($file['error']);
        $result[] = $f;
    }
    return $result;
}



/**
 * Upload files
 * 
 * @param uploadFile $file File who was uploaded
 * 
 * @return array Infos about upload
 */

function uploadFile($file)
{
    global $db;
        
    if($file->error){
        throw new Exception($file->error);
    }

    if($file->size > YUU_UPLOAD_MAX_FILE_SIZE*1048576){
        throw new Exception("This file is too large");
    }

    if(in_array($file->mime, YUU_UPLOAD_MIME_BLACKLIST)){
        throw new Exception("This MIME type is blacklisted");
    }

    if(pathinfo($file->name, PATHINFO_EXTENSION) !== null){
        if(in_array(pathinfo($file->name, PATHINFO_EXTENSION), YUU_UPLOAD_EXT_BLACKLIST)){
            throw new Exception("This extention is blacklisted");
        }
    }

    $sha1_file = sha1_file($file->tempfile);

    $q = $db->prepare("SELECT filename, COUNT(*) AS nb FROM files WHERE hash = (:hash) AND size = (:size)");
    $q->bindParam(":hash", $sha1_file);
    $q->bindParam(":size", $file->size);
    $q->execute();
    $res = $q->fetch();

    if($res["nb"] > 0){
        return [
            "name" => $file->name,
            "hash" => $sha1_file,
            "url" => YUU_UPLOAD_URI.rawurlencode($res['filename']),
            "size" => $file->size
        ];
    }

    $filename = random_name($file);

    $uploadedFile = YUU_UPLOAD_URI.rawurlencode($filename);

    if(!move_uploaded_file($file->tempfile, YUU_UPLOAD_FOLDER.$filename)){
        throw new Exception("Unable to move file to destination");
    }

    if (!chmod(YUU_UPLOAD_FOLDER.$filename, 0644)) {
        throw new Exception("Unable to change file permissions");
    }

    $date = time();
    $end_date = strtotime("+ 24 hours", $date);

    try {
        $time_log = date("d/m/Y - H:i", $date);
        file_put_contents(YUU_LOG_DIR, "[$time_log] New file uploaded : ".htmlspecialchars($file->name)."\n", FILE_APPEND);
        $q = $db->prepare("INSERT INTO `files`(`id`, `hash`, `size`, `original_name`, `filename`, `date`, `delete_date`)  VALUES (NULL, :hash, :size, :name, :filename, :date, :delete_date)");
        $q->bindParam(":hash", $sha1_file);
        $q->bindParam(":size", $file->size);
        $q->bindParam(":name", $file->name);
        $q->bindParam(":filename", $filename);
        $q->bindParam(":date", $date);
        $q->bindParam(":delete_date", $end_date);
        $q->execute();
        return [
            "name" => $file->name,
            "hash" => $sha1_file,
            "url" => YUU_UPLOAD_URI.rawurlencode($filename),
            "size" => $file->size,
            "delete_date" => $end_date
        ];
    } catch (\Throwable $th) {
        throw new Exception($th->getMessage());
    }

}

/**
 * Pretty print for size unit
 * 
 * @param int $bytes Size in bytes
 * 
 * @return string Pretty size
 */
function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}