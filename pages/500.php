<?php

http_response_code(500);
echo $twig->render("error.twig", [
    "page" => "500 - Internal Server Error",
    "code" => 500
]);