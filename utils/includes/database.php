<?php

require __DIR__."/settings.php";

/**
 * Database connection
 */

try {
    $db = new PDO("mysql:dbname=" . YUU_DB_NAME . ";host=" . YUU_DB_HOST . ";port=" . YUU_DB_PORT .";charset=UTF8", YUU_DB_USER, YUU_DB_PASS);
} catch (\Throwable $th) {
    throw new Exception("Unable to connect to database");   
}