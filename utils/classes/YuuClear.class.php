<?php

class YuuClear
{
	public $upload_folder;
	private $conn;

	public function __construct() {
	}

	public function setDatabase($host, $port, $user, $pass, $database, $charset) {
        try {
            $conn = new PDO("mysql:dbname=$database;host=$host;port=$port;charset=$charset",$user, $pass);
            $this->conn = $conn;
        } catch (\Throwable $th) {
            throw new Exception("Unable to connect to database", 500);
        }
	}
	
	public function clearFiles() {
		$q = $this->conn->query("SELECT * FROM uploads");
		$q->setFetchMode(PDO::FETCH_ASSOC);
		$files = $q->fetchAll();

		$time = time();

		foreach ($files as $file) {
			if($file["delete_date"] < $time){
				unlink($this->upload_folder.rawurlencode($file["filename"]));
				$this->conn->query("DELETE FROM uploads WHERE ID = ".$file["ID"]);
			}
		}
	}
}
