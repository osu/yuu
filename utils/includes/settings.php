<?php

/**
 * Database credential
 * 
 * @param string YUU_DB_HOST Database address
 * @param int YUU_DB_PORT Database port
 * @param string YUU_DB_USER Database username
 * @param string YUU_DB_PASS Database password
 * @param string  YUU_DB_NAME Database name
 */

define("YUU_DB_HOST", "localhost");
define("YUU_DB_PORT", 3306);
define("YUU_DB_USER", "yuush");
define("YUU_DB_PASS", "yuush");
define("YUU_DB_NAME", "yuush");

/**
 * Upload Configuration
 * @param string YUU_UPLOAD_URI Upload uri
 * @param string YUU_LOG_DIR Log file path
 * @param string YUU_WEBHOOK_DISCORD_URL Discord Webhook URL
 * @param string YUU_UPLOAD_FOLDER Upload folder
 * @param int YUU_UPLOAD_NAME_LENGTH Max length of random name 
 * @param int YUU_UPLOAD_NAME_MAX_RETRY Max atempts of retry for radom name
 * @param string YUU_UPLOAD_CHARSET Charset for uploaded files name
 * @param int YUU_UPLOAD_MAX_FILE_SIZE Max uploaded file size in MB
 * @param int YUU_UPLOAD_MAX_TIME Max time before file get deleted in hours
 * @param array YUU_UPLOAD_MIME_BLACKLIST Array of blacklist MIME_TYPE
 * @param array YUU_UPLOAD_EXT_BLACKLIST File extention blacklisted
 */

define("YUU_UPLOAD_URI", "https://s.yuu.sh/");
define("YUU_LOG_DIR", __DIR__."/../../log.txt");
define("YUU_UPLOAD_FOLDER", __DIR__."/../../uploads/");
define("YUU_UPLOAD_NAME_LENGTH", 5);
define("YUU_UPLOAD_NAME_MAX_RETRY", 15);
define("YUU_UPLOAD_CHARSET", "abcdefghijklmnopqrstuvwxyz123456789");
define("YUU_UPLOAD_MAX_FILE_SIZE", 5000);
define("YUU_UPLOAD_MAX_TIME", 24);
define("YUU_UPLOAD_MIME_BLACKLIST", []);
define("YUU_UPLOAD_EXT_BLACKLIST", ['nt','ps1','psm1','bash_profile','bashrc','profile']);