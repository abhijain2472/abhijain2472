<?php

namespace App\Http;

if ($_SERVER['HTTP_HOST'] == "localhost:8000") {
    define('DIR_HTTP_CURRENT_PAGE', "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    define('DIR_HTTP_HOME', "http://" . $_SERVER['HTTP_HOST'] . "/");
    define('DIR_WS_HOME', $_SERVER['DOCUMENT_ROOT']."\\");

    define('DIR_HTTP_CSS', DIR_HTTP_HOME . "css/");
    define('DIR_HTTP_JS', DIR_HTTP_HOME . "js/");
    define('DIR_HTTP_VENDOR', DIR_HTTP_HOME . "vendor/");

    define('DIR_HTTP_IMAGES', DIR_HTTP_HOME . "images/");
    define('DIR_WS_IMAGES', DIR_WS_HOME. "images\\");

    define('DIR_HTTP_CATEGORY_IMAGES', DIR_HTTP_IMAGES . "category/");
    define('DIR_WS_CATEGORY_IMAGES', DIR_WS_IMAGES. "category\\");

    define('DIR_HTTP_PRODUCT_IMAGES', DIR_HTTP_IMAGES . "product/");
    define('DIR_WS_PRODUCT_IMAGES', DIR_WS_IMAGES. "product\\");

    define('DIR_HTTP_SLIDER_IMAGES', DIR_HTTP_IMAGES . "slider/");
    define('DIR_WS_SLIDER_IMAGES', DIR_WS_IMAGES. "slider\\");

    define('DIR_HTTP_CSV', DIR_HTTP_IMAGES . "sample/");
    define('DIR_WS_CSV', DIR_WS_IMAGES. "sample\\");

    define('DIR_HTTP_UPLOAD', DIR_HTTP_IMAGES . "csv/");
    define('DIR_WS_UPLOAD', DIR_WS_IMAGES. "csv\\");

    define('DIR_HTTP_TEMPIMAGES', DIR_HTTP_IMAGES . "tempimages/");
    define('DIR_WS_TEMPIMAGES', DIR_WS_IMAGES. "tempimages\\");
} else {
    define('DIR_HTTP_CURRENT_PAGE', "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    define('DIR_HTTP_HOME', "http://" . $_SERVER['HTTP_HOST'] . "/public/");
    define('DIR_WS_HOME', $_SERVER['DOCUMENT_ROOT']."\\public\\");

    define('DIR_HTTP_CSS', DIR_HTTP_HOME . "css/");
    define('DIR_HTTP_JS', DIR_HTTP_HOME . "js/");
    define('DIR_HTTP_VENDOR', DIR_HTTP_HOME . "vendor/");

    define('DIR_HTTP_IMAGES', DIR_HTTP_HOME . "images/");
    define('DIR_WS_IMAGES', DIR_WS_HOME. "images\\");

    define('DIR_HTTP_CATEGORY_IMAGES', DIR_HTTP_IMAGES . "category/");
    define('DIR_WS_CATEGORY_IMAGES', DIR_WS_IMAGES. "category\\");

    define('DIR_HTTP_PRODUCT_IMAGES', DIR_HTTP_IMAGES . "product/");
    define('DIR_WS_PRODUCT_IMAGES', DIR_WS_IMAGES. "product\\");

    define('DIR_HTTP_SLIDER_IMAGES', DIR_HTTP_IMAGES . "slider/");
    define('DIR_WS_SLIDER_IMAGES', DIR_WS_IMAGES. "slider\\");

    define('DIR_HTTP_CSV', DIR_HTTP_IMAGES . "sample/");
    define('DIR_WS_CSV', DIR_WS_IMAGES. "sample\\");

    define('DIR_HTTP_UPLOAD', DIR_HTTP_IMAGES . "csv/");
    define('DIR_WS_UPLOAD', DIR_WS_IMAGES. "csv\\");

    define('DIR_HTTP_TEMPIMAGES', DIR_HTTP_IMAGES . "tempimages/");
    define('DIR_WS_TEMPIMAGES', DIR_WS_IMAGES. "tempimages\\");

}

function draw_image($path, $width = 100, $height = 100) {
    return "<div class='col-auto' id='previewDiv'>
        <img src='{$path}' alt='' id='previewImg' width='{$width}' height='{$height}'>
    </div>";
}

function draw_noimage($width = 100, $height = 100) {
    return "<div class='col-auto' id='previewDiv'>
        <img src='".DIR_HTTP_IMAGES."no-preview.png' alt='' id='previewImg' width='{$width}' height='{$height}'>
    </div>";
}

function objectToArray($array) {
    foreach ($array as $key => $value) {
        if(is_array($array[$key])) {
            $array[$key] = objectToArray($array[$key]);
        }
        $array = json_encode($array);
        $array = json_decode($array, true);
    }
    return $array;
}

function getDateFormat($date, $time = true) {
    if($time) {
        return date('m/d/Y h:i A', strtotime($date));
    }
    return date('M/d/y', strtotime($date));
}
