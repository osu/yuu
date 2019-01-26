<?php

http_response_code(404);
echo $twig->render("error.twig", [
    "page" => "404 - Not found",
    "code" => 404
]);