<?php

namespace App\Http;

if ($_SERVER['HTTP_HOST'] == "localhost:8000") {
    define('DIR_HTTP_HOME', "http://" . $_SERVER['HTTP_HOST'] . "/");
    define('DIR_WS_HOME', $_SERVER['DOCUMENT_ROOT']."\\");

    define('DIR_HTTP_CSS', DIR_HTTP_HOME . "css/");
    define('DIR_HTTP_JS', DIR_HTTP_HOME . "js/");
    define('DIR_HTTP_VENDOR', DIR_HTTP_HOME . "vendor/");

    define('DIR_HTTP_IMAGES', DIR_HTTP_HOME . "images/");
    define('DIR_WS_IMAGES', DIR_WS_HOME. "images\\");

    define('DIR_HTTP_CATEGORY_IMAGES', DIR_HTTP_IMAGES . "category/");
    define('DIR_WS_CATEGORY_IMAGES', DIR_WS_IMAGES. "category\\");
}