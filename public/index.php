<?php

require __DIR__."/../utils/Loader.php";

$router = new Router(
	"home", true
);

$twig_loader = new Twig_Loader_Filesystem(
	__DIR__."/../templates/"
);

if(Web::debug == true){
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
    "name" => Web::name,
    "subtitle" => Web::subtitle,
    "maxsize" => Web::max_upload_size,
    "pages" => Web::pages
]);

try {
    if(!file_exists(__DIR__."/../routes/$router->request.php")){
        require __DIR__."/../routes/404.php";
        exit;
    }else{
        require __DIR__."/../routes/$router->request.php";
        exit;
    }
} catch (\Throwable $th) {
	require __DIR__."/../routes/500.php";
    exit;
}