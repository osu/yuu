<?php

class Response
{
    static function success($text) {
        http_response_code(200);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            "success" => true,
            "items" => $text
        ], JSON_PRETTY_PRINT);
    }

    static function error($text, $code = 500) {
        http_response_code($code);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            "success" => false,
            "code" => $code,
            "error" => $text
        ], JSON_PRETTY_PRINT);
    }
}
