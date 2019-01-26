<?php

require __DIR__."/../utils/Loader.php";

$router = new Router(website::get_param, website::home, true);
$twig_loader = new Twig_Loader_Filesystem(__DIR__."/../views/");

if(website::debug == true){
    ini_set('display_errors','on');
    error_reporting(E_ALL);
    $twig = new Twig_Environment($twig_loader, [
        'debug' => true
    ]);
}else{
    ini_set('display_errors','false');
    $twig = new Twig_Environment($twig_loader, [
        'cache' => __DIR__."/../cache",
    ]);
}

$twig->addGlobal("global", [
    "name" => website::name,
    "subtitle" => website::subtitle,
    "maxsize" => upload::maxsize,
    "pages" => website::pages
]);

try {
    if(!file_exists(__DIR__."/../pages/$router->request.php")){
        require __DIR__."/../pages/404.php";
        exit;
    }else{
        require __DIR__."/../pages/$router->request.php";
        exit;
    }
} catch (\Throwable $th) {
    require __DIR__."/../pages/500.php";
    exit;
}