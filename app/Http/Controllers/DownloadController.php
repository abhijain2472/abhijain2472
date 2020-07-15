<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function download($file) {
        $headers = array(
            'Content-Type: application/csv',
        );
        switch ($file) {
            case 'product':
                echo DIR_HTTP_CSV."import_blog.zip";
                break;
        }
        exit;
    }
}
