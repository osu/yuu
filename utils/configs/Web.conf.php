<?php

class Web
{
	const name = "Yuu.sh";
	const subtitle = "Free file hosting";
	const debug = false;
	const pages = [
        [
            "url" => "/home",
            "name" => "Home"
        ],
        [
            "url" => "https://www.patreon.com/nionionio",
            "name" => "Make a donation"
        ],
        [
            "url" => "/faq",
            "name" => "FAQ"
        ],
        [
            "url" => "/tools",
            "name" => "Tools"
        ],
        [
            "url" => "/contact",
            "name" => "Contact"
        ],
    ];
    const database = [
        "hostname" => "",
        "port" => 3306,
        "user" => "",
        "passwd" => "",
        "database" => "",
        "charset" => "utf8mb4"
	];
	const clamav = [
		"host" => "127.0.0.1",
		"port" => 3310
	];
	const max_upload_size = "10000";
	const filename_size = 6;
	const max_filename_retry = 15;
	const unautorized_ext = [];
    const chars = "azertyuiopqsdfghjklmwxcvbn1234567890";
    const default_uri = "https://s.yuu.sh/";
    const uploadFolder = __DIR__."/../../uploads/";
}
