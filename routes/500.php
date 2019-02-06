<?php

http_response_code(404);
echo $twig->render("error.twig", [
    "page" => "500 - Server Error",
    "code" => 500
]);