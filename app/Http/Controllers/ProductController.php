<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use function App\Http\draw_image;
use function App\Http\draw_noimage;
use function App\Http\getDateFormat;
use function App\Http\objectToArray;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $products = Product::with(['category'])->join('categories', 'categories.category_id', '=', 'products.product_category_id')->orderBy('categories.sort_order')->get(['products.*']);
        // foreach ($products as $key => $value) {
        //     if($value['product_image']) {
        //         $products[$key]['product_image'] = draw_image(DIR_HTTP_PRODUCT_IMAGES.$value->product_id."/".$value->product_image, 80, 70);
        //     } else {
        //         $products[$key]['product_image'] = draw_noimage(80, 70);
        //     }
        // }
        return view('product.index', /**compact('products') */);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $addText = "Add";
        $description = "Here you can add products.";
        $product = new Product();
        $prod = $product->getTableColumns();
        $prod = array_flip($prod);
        foreach ($prod as $key => $value) {
            $prod[$key] = "";
        }
        $prod['is_new_arrival'] = "checked";
        $prod['product_image'] = draw_noimage(220, 150);
        $prod['status'] = "checked";
        $catrgories = Category::all()->sortBy('sort_order');
        return view('product.create', compact('addText', 'description', 'product', 'catrgories', 'prod'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product_name = $request->input('product_name');
        $status = $request->input('status');
        $discount = $request->input('discount');
        $product_price = $request->input('product_price');
        $product_category_id = $request->input('product_category_id');
        $is_hot_product = $request->input('is_hot_product');
        $is_new_arrival = $request->input('is_new_arrival');
        $product_details = $request->input('product_details');

        $prdduct = new Product();
        $prdduct->product_name = $product_name;
        $prdduct->product_price = $product_price;
        $prdduct->discount = $discount;
        $prdduct->product_details = $product_details;
        $prdduct->is_hot_product = (isset($is_hot_product)) ? "1" : "0";
        $prdduct->is_new_arrival = (isset($is_new_arrival)) ? "1" : "0";
        $prdduct->product_category_id = $product_category_id;
        $prdduct->user_id = 0;
        $prdduct->status = (isset($status)) ? "1" : "0";

        if($prdduct->save()) {
            $product_image = $request->file('product_image');
            if($product_image != null) {
                $ext = $product_image->getClientOriginalExtension();
                if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "bmp") {
                    $filename = date('Y_m_d_h_i_s') . "." . $ext;
                    $destination = DIR_WS_PRODUCT_IMAGES.$prdduct->product_id;
                    if(!file_exists($destination)) {
                        mkdir($destination, 0777, true);
                    }
                    $imageAdded = false;
                    if($request->cookie('cropped_image_upload')) {
                        if(copy($request->cookie('cropped_image_upload'), $destination."\\".$filename)) {
                            unlink($request->cookie('cropped_image_upload'));
                            $imageAdded = true;
                        }

                    } else {
                        if($product_image->move($destination, $filename)) {
                            $imageAdded = true;
                        }
                    }
                    if($imageAdded == TRUE) {
                        $prdduct = Product::find($prdduct->product_id);
                        $prdduct->product_image = $filename;
                        $prdduct->save();
                    }
                } else {
                    return redirect('add-product/'.$prdduct->product_id)->with('success', 'Only PNG/JPEG/JPG/BMP is allowed.');
                }
            }
        } else {
            return redirect()->back()->with('fail', 'Error adding product.');
        }
        return redirect('add-product/'.$prdduct->product_id)->with('success', 'Product is added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        $product = new Collection($product);
        $product = $product->all();
        if($product['product_image']) {
            $product['product_image'] = draw_image(DIR_HTTP_PRODUCT_IMAGES.$product['product_id']."/".$product['product_image'], 220, 150);
        } else {
            $product['product_image'] = draw_noimage(220, 150);
        }
        $product['status'] = ($product['status'] == "1") ? "checked" : "";
        $product['is_hot_product'] = ($product['is_hot_product'] == "1") ? "checked" : "";
        $product['is_new_arrival'] = ($product['is_new_arrival'] == "1") ? "checked" : "";
        $addText = "Update";
        $description = "Here you can edit products.";
        $catrgories = Category::all()->sortBy('sort_order');
        return view('product.create', compact('product', 'description', 'addText', 'catrgories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product_name = $request->input('product_name');
        $status = $request->input('status');
        $discount = $request->input('discount');
        $product_price = $request->input('product_price');
        $product_category_id = $request->input('product_category_id');
        $is_hot_product = $request->input('is_hot_product');
        $is_new_arrival = $request->input('is_new_arrival');
        $product_details = $request->input('product_details');

        $prdduct = Product::find($id);
        $prdduct->product_name = $product_name;
        $prdduct->product_price = $product_price;
        $prdduct->discount = $discount;
        $prdduct->product_details = $product_details;
        $prdduct->is_hot_product = (isset($is_hot_product)) ? "1" : "0";
        $prdduct->is_new_arrival = (isset($is_new_arrival)) ? "1" : "0";
        $prdduct->product_category_id = $product_category_id;
        $prdduct->user_id = 0;
        $prdduct->status = (isset($status)) ? "1" : "0";

        if($prdduct->save()) {
            $product_image = $request->file('product_image');
            if($product_image != null) {
                $ext = $product_image->getClientOriginalExtension();
                if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "bmp") {
                    $filename = date('Y_m_d_h_i_s') . "." . $ext;
                    $destination = DIR_WS_PRODUCT_IMAGES.$prdduct->product_id;
                    if(!file_exists($destination)) {
                        mkdir($destination, 0777, true);
                    }
                    if($product_image->move($destination, $filename)) {
                        $prdduct = Product::find($prdduct->product_id);
                        unlink($destination."/".$prdduct->product_image);
                        $prdduct->product_image = $filename;
                        $prdduct->save();
                    }
                } else {
                    return redirect('add-product/'.$prdduct->product_id)->with('success', 'Only PNG/JPEG/JPG/BMP is allowed.');
                }
            }
        } else {
            return redirect()->back()->with('fail', 'Error updating product.');
        }
        return redirect('add-product/'.$prdduct->product_id)->with('success', 'Product is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function getProduct($id) {
        $product = Product::find($id);
        $product->category;
        echo json_encode($product);
        exit;
    }

    public function deleteProduct($id) {
        $product = Product::find($id);
        $path = DIR_WS_PRODUCT_IMAGES.$id;
        if(Product::destroy($id)) {
            if(file_exists($path."\\".$product->product_image)) {
                unlink($path."\\".$product->product_image);
            }
            rmdir($path);
            return redirect()->back()->with('success', 'Product is deleted successfully.');
        }
        return redirect()->back()->with('fail', 'Error deleting product.');
    }

    public function changeStatus(Request $request, $id) {
        $product = Product::find($id);
        $product->status = $request->input('status');
        if($product->save()) {
            echo "success";
        }
        exit;
    }

    public function importPage(Request $request) {
        $addText = "Import";
        $description = "Here you can import products directly";
        $categories = Category::all()->sortBy('sort_order');
        return view('product.import', compact('addText', 'description', 'categories'));
    }

    public function importSave(Request $request) {
        $data = $request->session()->get('record_add');
        $file = pathinfo(array_pop($data), PATHINFO_DIRNAME);
        $dataCount = count($data);
        $count = 0;
        foreach ($data as $key => $value) {
            $product = new Product();
            $product->product_name = $value['product_name'];
            $product->product_price = $value['product_price'];
            $product->discount = $value['discount'];
            $product->product_details = $value['product_details'];
            $product->is_hot_product = $value['is_hot_product'];
            $product->is_new_arrival = $value['is_new_arrival'];
            $product->product_category_id = $value['product_category_id'];
            $product->user_id = $value['user_id'];
            $product->status = $value['status'];
            $image = $value['product_image'];
            if($product->save()) {
                if(!empty($image)) {
                    $ext = pathinfo($image, PATHINFO_EXTENSION);
                    if($ext == "png" || $ext == "jpeg" || $ext == "jpg" || $ext == "bmp") {
                        $destination = DIR_WS_PRODUCT_IMAGES.$product->product_id."\\";
                        $filename = date('Y_m_d_h_i_s') . "." . $ext;
                        if(!file_exists($destination)) {
                            mkdir($destination, 0777, true);
                        }
                        if(copy($file."\\".$image, $destination.$filename) == TRUE) {
                            $product = Product::find($product->product_id);
                            $product->product_image = $filename;
                            $product->save();
                        }
                    }
                }
                $count++;
            }
        }
        if($count > 0 && $count == $dataCount) {
            $request->session()->flash('success', 'Data is added successfully.');
            $this->delete_directory($file);
            unlink($file.".zip");
        } elseif ($count < $dataCount) {
            $request->session()->flash('fail', 'Some of the data is missing.');
        } elseif($count == 0) {
            $request->session()->flash('fail', 'Failed to store data.');
        }
        $request->session()->forget('record_add');
        echo 'done';
        exit;
    }

    function delete_directory($dirname) {
        if (is_dir($dirname))
          $dir_handle = opendir($dirname);
        if (!$dir_handle)
            return false;
        while($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname."/".$file))
                    unlink($dirname."/".$file);
                else
                    $this->delete_directory($dirname.'/'.$file);
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }

    public function getproductList(Request $request) {
        $columnlist = array('products.product_id', 'products.product_name', 'products.product_image', 'products.product_category_id', 'products.product_price', 'products.discount', 'products.created_at', 'products.updated_at', 'products.status');
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $request->input('order')[0];
        $products = Product::all();
        $totalRec = count($products);
        $products = DB::table('products')
        ->join('categories', 'categories.category_id', '=', 'products.product_category_id')
        ->orderBy('categories.sort_order')
        ->select([DB::raw('FOUND_ROWS() as row_count'), 'products.*', 'categories.name', DB::raw('IF(discount > 0, discount, 0) as discount')]);
        if($start > 0 && $length != "") {
            $products->offset($start)->limit($length);
        } else {
            $products->limit($length);
        }
        if(!empty($order)) {
            $products->orderBy($columnlist[$order['column']], $order['dir']);
        }
        $products = $products->get();
        $filterRec = count($products);
        $htmlArray = array();
        foreach ($products as $key => $value) {
            $rec = array();
            $rec[] = $key+1;
            $rec[] = $value->product_name;
            $image = $value->product_image;
            $path = DIR_WS_PRODUCT_IMAGES.$value->product_id."\\".$image;
            if($image != null && file_exists($path)) {
                $path = DIR_HTTP_PRODUCT_IMAGES.$value->product_id."/".$image;
                $rec[] = draw_image($path,80,70);
            } else {
                $rec[] = draw_noimage(80,70);
            }
            $rec[] = $value->name;
            $rec[] = $value->product_price;
            $rec[] = $value->discount;
            $rec[] = getDateFormat($value->created_at);
            $rec[] = getDateFormat($value->updated_at);
            $checked = '';
            if($value->status) {
                $checked = 'checked';
            }
            $rec[] = "<div class='custom-control custom-switch'>
            <input type='checkbox' class='custom-control-input ajax-status' id='status_{$value->product_id}' value='on' {$checked}> <label class='custom-control-label' for='status_{$value->product_id}'></label>
        </div>";
            $rec[] = "<a href='/add-product/{$value->product_id}' title='Edit Product'>
            <i class='far fa-edit'></i>
        </a>
        <a href='javascript:return false;' onclick='getWarning({$value->product_id})' data-toggle='modal' data-target='#deleteModal' class='text-danger' title='Delete Product'>
            <i class='far fa-trash-alt'></i>
        </a>";
            $htmlArray[] = $rec;
        }
        return array(
            'draw'=>$request->input('draw'),
            'recordsTotal'=>$totalRec,
            'recordsFiltered'=>$totalRec,
            'data'=>$htmlArray
        );
    }
}
