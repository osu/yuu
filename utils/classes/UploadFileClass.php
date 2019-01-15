<?php

class UploadFile
{
    public $name;
    public $mime;
    public $size;
    public $tempfile;
    public $error;

    public function errorCode($code){
        switch ($code) { 
            case 0:
                $message = null;
                break;
            case 1: 
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; 
                break; 
            case 2: 
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form"; 
                break; 
            case 3: 
                $message = "The uploaded file was only partially uploaded"; 
                break; 
            case 4: 
                $message = "No file was uploaded"; 
                break; 
            case 6: 
                $message = "Missing a temporary folder"; 
                break; 
            case 7: 
                $message = "Failed to write file to disk"; 
                break; 
            case 8: 
                $message = "File upload stopped by extension"; 
                break; 

            default: 
                $message = "Unknown upload error"; 
                break; 
        } 
        $this->error = $message; 
    }
}
