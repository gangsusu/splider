<?php
require_once 'vendor/autoload.php';
//糗事百科文章抓取
(new \App\Spider())->qiu_run();
//voa单词抓取
(new \App\Spider())->voa_run();