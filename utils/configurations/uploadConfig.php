<?php

class upload
{
    const maxsize = "5120";
    const namesize = 6;
    const filename_retry = 15;
    const unautorized_ext = ["js"];
    const chars = "azertyuiopqsdfghjklmwxcvbn1234567890";
    const uri = "https://s.yuu.sh/";
    const uploadFolder = __DIR__."/../../uploads/";
    const file_max_life = "24";
}
