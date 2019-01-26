<?php

class YuuUploader
{

    public $file;

    public $chars;
    public $uploadURI;
    public $filenameLength;
    public $filenameMaxRetry;
    public $maxFilesize;
    public $extBlacklist;
    public $uploadFolder;

    private $conn;

    public function __construct($file) {
        $this->file = $file;
        if(!$this->isValidFile()){
            throw new Exception("Invalid file", 400);
        }
        if($file["error"] !== 0){
            throw new Exception($this->errorCode(), 500);
        }
    }

    private function isValidFile() {
        if(!is_array($this->file)){
            return false;
        }elseif(
            !array_key_exists("name", $this->file)
            || !array_key_exists("type", $this->file)
            || !array_key_exists("tmp_name", $this->file)
            || !array_key_exists("error", $this->file)
            || !array_key_exists("size", $this->file)
        ){
            return false;
        }elseif(
            is_array($this->file["name"])
            || is_array($this->file["type"])
            || is_array($this->file["tmp_name"])
            || is_array($this->file["error"])
            || is_array($this->file["size"])
        ){
            return false;
        }else{ 
            return true;
        }
    }

    private function errorCode() {
        switch ($this->file["error"]) { 
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
        return $message;
    }

    public function setDatabase($host, $port, $user, $pass, $database, $charset) {
        try {
            $conn = new PDO("mysql:dbname=$database;host=$host;port=$port;charset=$charset",$user, $pass);
            $this->conn = $conn;
        } catch (\Throwable $th) {
            throw new Exception("Unable to connect to database", 500);
        }
    }

    private function randomFilename() {

        $ext = pathinfo($this->file["name"], PATHINFO_EXTENSION);

        do {
        
            if($this->filenameMaxRetry-- === 0){
                throw new Exception("Unable to find a unique name", 500);
            }
    
            $name = "";
            
            for ($i=0; $i < $this->filenameLength; $i++) { 
                $name .= $this->chars[rand(0, strlen($this->chars)-1)];
            }
    
            if (isset($ext) && $ext !== "") {
                $name .= ".".$ext;
            }
            
            $q = $this->conn->prepare("SELECT COUNT(*) FROM uploads WHERE filename = :name");
            $q->bindParam(":name", $name);
            $q->execute();
            $res = $q->fetchColumn();
    
        } while ($res > 0);
    
        return $name;
    }

    public function uploadFile() {

        $ext = pathinfo($this->file["name"], PATHINFO_EXTENSION);
    
        if($this->file["size"] > $this->maxFilesize*1048576){
            throw new Exception("This file is too large", 400);
        }
    
        if($ext !== null && $ext !== ""){
            if(in_array($ext, $this->extBlacklist)){
                throw new Exception("This extention is blacklisted", 400);
            }
        }
    
        $sha1_file = sha1_file($this->file["tmp_name"]);
    
        $q = $this->conn->prepare("SELECT filename, COUNT(*) AS nb FROM uploads WHERE hash = (:hash) AND size = (:size)");
        $q->bindParam(":hash", $sha1_file);
        $q->bindParam(":size", $this->file["size"]);
        $q->execute();
        $res = $q->fetch();
    
        if($res["nb"] > 0){
            return [
                "name" => $this->file["name"],
                "hash" => $sha1_file,
                "url" => $this->uploadURI.rawurlencode($res['filename']),
                "size" => $this->file["size"]
            ];
        }
    
        $filename = $this->randomFilename();
    
        $uploadedFile = $this->uploadURI.rawurlencode($filename);
    
        if(!move_uploaded_file($this->file["tmp_name"], $this->uploadFolder.$filename)){
            throw new Exception("Unable to move file to destination", 500);
        }
    
        if (!chmod($this->uploadFolder.$filename, 0644)) {
            throw new Exception("Unable to change file permissions", 500);
        }
    
        $date = time();
    
        try {
            $q = $this->conn->prepare("
            INSERT INTO `uploads`(`name`, `filename`, `size`, `hash`, `upload_date`) 
            VALUES (:name, :filename, :size, :hash, :date)
            ");
            $q->bindParam(":name", $this->file["name"]);
            $q->bindParam(":filename", $filename);
            $q->bindParam(":size", $this->file["size"]);
            $q->bindParam(":hash", $sha1_file);
            $q->bindParam(":date", $date);
            $q->execute();
            return [
                "name" => $this->file["name"],
                "hash" => $sha1_file,
                "url" => $this->uploadURI.rawurlencode($filename),
                "size" => $this->file["size"],
                "upload_date" => $date
            ];
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 500);
        }
    }
}
