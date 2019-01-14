<?php

require __DIR__."/../utils/includes/functions.php";
require __DIR__."/../utils/classes/ResponseClass.php";

$type = (isset($_GET['type'])) ? $_GET['type'] : 'json';
$message = new Response($type);
$res = array();

if(isset($_FILES['upload'])){

    $files = remakeFile($_FILES['upload']);

    try {
        
        foreach ($files as $file) {
            $res[] = uploadFile($file);
        }

        if(count($res) < 1){
            $message->error(400, "Invalid input file");
        }else{
            $message->success($res);
        }

    } catch (\Throwable $th) {
        
        $message->error(500, $th->getMessage());

    }

}else{

    $message->error(400, "No input file");

}