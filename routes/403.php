<?php

http_response_code(403);
echo $twig->render("error.twig", [
    "page" => "403 - Unauthorized",
    "code" => 403
]);