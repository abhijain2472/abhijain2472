<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use ZipArchive;

class ImportController extends Controller
{
    public function upload(Request $request) {
        $request->session()->forget('file_upload');
        $request->session()->forget('record_add');
        $file = $request->file("file_upload");
        if($file != null) {
            $ext = $file->getClientOriginalExtension();
            if($ext == "csv" || $ext == "xlsx") {
                $destination = DIR_WS_IMAGES."csv\\";
                $fileName = $file->getClientOriginalName();
                if(!file_exists($destination)) {
                    mkdir($destination, 0777, true);
                }
                if($file->move($destination, $fileName)) {
                    $request->session()->put('file_upload', $fileName);
                    $html = $this->showvalidate('product');
                    return json_encode(array("success" => "success", "data" => $html));
                }
            }

            if($ext == "zip") {
                $destination = DIR_WS_IMAGES."csv\\";
                $fileName = $file->getClientOriginalName();
                if(!file_exists($destination)) {
                    mkdir($destination, 0777, true);
                }
                if($file->move($destination, $fileName)) {
                    $zip = new ZipArchive;
                    $res = $zip->open($destination.$fileName);
                    $finalFileName = "";
                    if ($res === TRUE) {
                        $destination = DIR_WS_UPLOAD.pathinfo(DIR_WS_UPLOAD.$fileName, PATHINFO_FILENAME);
                        if(file_exists($destination)) {
                            delete_directory($destination);
                        }
                        if(!file_exists($destination)) {
                            mkdir($destination, 0777, true);
                        }
                        $zipobj = zip_open(DIR_WS_UPLOAD.$fileName);
                        while ($zip_entry = zip_read($zipobj)) {
                            if(pathinfo(zip_entry_name($zip_entry), PATHINFO_EXTENSION) == 'csv') {
                                $finalFileName = zip_entry_name($zip_entry);
                            }
                        }
                        $zip->extractTo($destination);
                        $zip->close();
                    }
                    $request->session()->put('file_upload', $destination."\\".$finalFileName);
                    $html = $this->showvalidate('product');
                    return json_encode(array("success" => "success", "data" => $html));
                }
            }
        }
        exit;
    }

    public function showvalidate($type){
        $file = Session::get('file_upload');
        $dataArr = $this->csvToArray($file);
        $outputArr = array();
        $success = $skipped = 0;

        switch ($type) {
            case 'product':
                $categories = Category::all();
                $categories = Category::getArray($categories);
                $cate = array();
                foreach ($categories as $key => $value) {
                    $cate[] = $value['category_id'];
                }

                if($dataArr) {
                    $headers = array_keys($dataArr[0]);
                    $outputArr = "<table class='table bg-white table-bordered'><thead><tr>";
                    foreach ($headers as $key => $value) {
                        $outputArr .= "<th>". ucwords(str_replace("_", " ", $value)) ."</th>";
                    }
                    $outputArr .= "</tr></thead><tbody>";
                    $successData = array();
                    foreach ($dataArr as $key => $value) {
                        $product_name = $value['product_name'];
                        $product_price = $value['product_price'];
                        $product_details = $value['product_details'];
                        $is_hot_product = $value['is_hot_product'];
                        $is_new_arrival = $value['is_new_arrival'];
                        $product_category_id = $value['product_category_id'];
                        $status = $value['status'];
                        $user_id = $value['user_id'];
                        $err_product_name = $err_product_price = $err_is_hot_product = $err_product_details = $err_is_new_arrival = $err_status = $err_user_id = $err_product_category_id = "";
                        if($product_name == "") {
                            $err_product_name = "bg-danger text-white";
                        }

                        if($product_price == "") {
                            $err_product_price = "bg-danger text-white";
                        }

                        if($product_details == "") {
                            $err_product_details = "bg-danger text-white";
                        }

                        if($is_hot_product != '1' && $is_hot_product != '0') {
                            $err_is_hot_product = "bg-danger text-white";
                        }

                        if($is_new_arrival != '1' && $is_new_arrival != '0') {
                            $err_is_new_arrival = "bg-danger text-white";
                        }

                        if($status != '1' && $status != '0') {
                            $err_status = "bg-danger text-white";
                        }

                        if(!in_array($product_category_id, $cate)) {
                            $err_product_category_id = "bg-danger text-white";
                        }

                        if($user_id == "") {
                            $err_user_id = "bg-danger text-white";
                        }

                        $outputArr .= "<tr><td class='{$err_product_name}'>{$value['product_name']}</td><td class='{$err_product_price}'>{$value['product_price']}</td><td class=''>{$value['product_image']}</td><td class=''>{$value['discount']}</td><td class='{$err_product_details}'>{$value['product_details']}</td><td class='{$err_is_hot_product}'>{$value['is_hot_product']}</td><td class='{$err_is_new_arrival}'>{$value['is_new_arrival']}</td><td class='{$err_product_category_id}'>{$value['product_category_id']}</td><td class='{$err_status}'>{$value['status']}</td><td class='{$err_user_id}'>{$value['user_id']}</td></tr>";

                        if($err_product_name != "" || $err_product_price != "" || $err_product_details != "" || $err_user_id != "" || $err_is_hot_product != "" || $err_is_new_arrival != "" || $err_status != "" || $err_product_category_id != "") {
                            $skipped++;
                        }

                        if($err_product_name == "" && $err_product_price == "" && $err_product_details == "" && $err_user_id == "" && $err_is_hot_product == "" && $err_is_new_arrival == "" && $err_status == "" && $err_product_category_id == "") {
                            $success++;
                            $successData[] = $value;
                        }
                    }
                    $successData['file'] = $file;
                    Session::put('record_add', $successData);
                    $outputArr .= "</tbody></table>";
                }
                break;
            default:

                break;
        }
        return array("html" => $outputArr, "rowsuccess" => $success, "rowskipped" => $skipped);
    }

    private function csvToArray($filename = '', $delimiter = ',') {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                    foreach($header as $key => $value) {
                        $header[$key] = strtolower(str_replace(" ", "_", $value));
                    }
                }
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }
}
