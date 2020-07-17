<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

use function App\Http\draw_image;

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

    public function uploadcrop(Request $request) {
        $imagesArray = $request->session()->get('image_array');
        $image = $request->input('image');
        $image = explode(";", $image);
        $image = explode(',', $image[1]);
        $imgData = base64_decode($image[1]);
        $filename = date('Y_m_d_h_i_s') . ".png";
        file_put_contents(DIR_WS_TEMPIMAGES.$filename, $imgData);
        $respose = new Response(DIR_HTTP_TEMPIMAGES.$filename);
        $imagesArray[] = DIR_WS_TEMPIMAGES.$filename;
        $request->session()->put('image_array', $imagesArray);
        $respose->withCookie(cookie('cropped_image_upload', DIR_WS_TEMPIMAGES.$filename, 10));
        return $respose;
        exit;
    }
}
