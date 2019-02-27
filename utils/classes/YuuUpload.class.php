<?php

class YuuUpload
{
	private $file;

	public $rand_length = 6;
	public $retry_count = 15;
	public $chars = "azertyuiopqsdfghjklmwxcvbn1234567890";
	public $upload_folder;

	public $upload_size = 10000;
	public $ext_disabled = [];
	public $delete_time = null;

	public $uri;

	public $clamav_host = "127.0.0.1";
	public $clamav_port = 3310;

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
        }elseif(!file_exists($this->file["tmp_name"])){
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
        
            if($this->retry_count-- === 0){
                throw new Exception("Unable to find a unique name", 500);
            }
    
            $name = "";
            
            for ($i=0; $i < $this->rand_length; $i++) { 
                $name .= $this->chars[rand(0, strlen($this->chars)-1)];
            }
    
            if (isset($ext) && $ext !== "") {
                $name .= ".".$ext;
            }
            
            try {
				$q = $this->conn->prepare("SELECT COUNT(*) FROM uploads WHERE filename = :name");
				$q->bindParam(":name", $name);
				$q->execute();
				$res = $q->fetchColumn();
			} catch (\Throwable $th) {
				throw new Exception("Unable to make request to database", 500);
			}
    
        } while ($res > 0);
    
        return $name;
	}

	private function clamavChecker() {
		$socket = socket_create(AF_INET, SOCK_STREAM, 0);
		if(socket_connect($socket, $this->clamav_host, $this->clamav_port)) {
			try {
				$command = "SCAN ".realpath($this->file["tmp_name"]);

				socket_send($socket, $command, strlen($command), 0);
				socket_recv($socket, $res, 20000, 0);
				socket_close($socket);

				$scan = trim(substr(strrchr($res, ":"), 1));

				if($scan == "OK"){
					return true;
				}else{
					return "unsafe";
				}
			} catch (\Throwable $th) {
				return false;
			}	
		}else{
			return false;
		}
	}
	
	public function uploadFile() {

        $ext = pathinfo($this->file["name"], PATHINFO_EXTENSION);
    
        if($this->file["size"] > $this->upload_size*1048576){
            throw new Exception("This file is too large", 400);
        }
    
        if($ext !== null && $ext !== ""){
            if(in_array($ext, $this->ext_disabled)){
                throw new Exception("This extention is blacklisted", 400);
            }
		}
		
		/*$checker = $this->clamavChecker();

		if(!$checker) {
			throw new Exception("Unable to check your file", 500);
		}

		if($checker === "unsafe") {
			throw new Exception("Your file is unsafe", 400);
		}*/
    
        $sha1_file = sha1_file($this->file["tmp_name"]);
    
        try {
			$q = $this->conn->prepare("SELECT filename, upload_date, delete_date, COUNT(*) AS nb FROM uploads WHERE hash = (:hash) AND size = (:size)");
			$q->bindParam(":hash", $sha1_file);
			$q->bindParam(":size", $this->file["size"]);
			$q->execute();
			$res = $q->fetch();
		} catch (\Throwable $th) {
			throw new Exception($th->getMessage(), 500);
		}
    
        if($res["nb"] > 0){
            return [
                "name" => $this->file["name"],
                "hash" => $sha1_file,
                "url" => $this->uri.rawurlencode($res['filename']),
				"size" => $this->file["size"],
				"upload_date" => $res["upload_date"],
				"delete_date" => $res["delete_date"]
            ];
        }
    
        $filename = $this->randomFilename();
    
        $uploadedFile = $this->uri.rawurlencode($filename);
    
        if(!move_uploaded_file($this->file["tmp_name"], $this->upload_folder.$filename)){
            throw new Exception("Unable to move file to destination", 500);
        }
    
        if (!chmod($this->upload_folder.$filename, 0644)) {
            throw new Exception("Unable to change file permissions", 500);
        }
    
		$date = time();
		if($this->delete_time == 0){
			$this->delete_time = null;
		}else{
			$this->delete_time = strtotime("+ " . $this->delete_time . " hours", $date);
		}
    
        try {
            $q = $this->conn->prepare("
            INSERT INTO `uploads`(`name`, `filename`, `size`, `hash`, `upload_date`, `delete_date`) 
            VALUES (:name, :filename, :size, :hash, :udate, :ddate)
            ");
            $q->bindParam(":name", $this->file["name"]);
            $q->bindParam(":filename", $filename);
            $q->bindParam(":size", $this->file["size"]);
            $q->bindParam(":hash", $sha1_file);
			$q->bindParam(":udate", $date);
			$q->bindParam(":ddate", $this->delete_time);
			$q->execute();
            return [
                "name" => $this->file["name"],
                "hash" => $sha1_file,
                "url" => $this->uri.rawurlencode($filename),
                "size" => $this->file["size"],
				"upload_date" => $date,
				"delete_time" => $this->delete_time
            ];
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 500);
        }
    }
}
