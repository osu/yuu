<?php

class website
{
    const name = "Yuu.sh";
    const subtitle = "Free and temporary file hosting<br>We keep your files online 1 day";
    const version = "2.0.0";
    const home = "home";
    const get_param = "page";
    const debug = true;
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
        "hostname" => "localhost",
        "port" => 3306,
        "user" => "yuush",
        "passwd" => "yuush",
        "database" => "yuush",
        "charset" => "utf8mb4"
    ];
}
