<?php

if(!isset($router->args)
   || count($router->args) == 0
   || !file_exists(__DIR__."/api/" . $router->args[0] . ".php")) {
    Response::error("Not found", 404);
    exit;
}else{
    require __DIR__."/api/" . $router->args[0] . ".php";
    exit;
}