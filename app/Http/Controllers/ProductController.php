<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use function App\Http\draw_image;
use function App\Http\draw_noimage;
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
        $products = Product::with(['category'])->join('categories', 'categories.category_id', '=', 'products.product_category_id')->orderBy('categories.sort_order')->get(['products.*']);
        foreach ($products as $key => $value) {
            if($value['product_image']) {
                $products[$key]['product_image'] = draw_image(DIR_HTTP_PRODUCT_IMAGES.$value->product_id."/".$value->product_image, 80, 70);
            } else {
                $products[$key]['product_image'] = draw_noimage(80, 70);
            }
        }
        return view('product.index', compact('products'));
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
        $prod['is_new_arriaval'] = "checked";
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
        $is_new_arriaval = $request->input('is_new_arriaval');
        $product_details = $request->input('product_details');

        $prdduct = new Product();
        $prdduct->product_name = $product_name;
        $prdduct->product_price = $product_price;
        $prdduct->discount = $discount;
        $prdduct->product_details = $product_details;
        $prdduct->is_hot_product = (isset($is_hot_product)) ? "1" : "0";
        $prdduct->is_new_arriaval = (isset($is_new_arriaval)) ? "1" : "0";
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
        $product['is_new_arriaval'] = ($product['is_new_arriaval'] == "1") ? "checked" : "";
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
        $is_new_arriaval = $request->input('is_new_arriaval');
        $product_details = $request->input('product_details');

        $prdduct = Product::find($id);
        $prdduct->product_name = $product_name;
        $prdduct->product_price = $product_price;
        $prdduct->discount = $discount;
        $prdduct->product_details = $product_details;
        $prdduct->is_hot_product = (isset($is_hot_product)) ? "1" : "0";
        $prdduct->is_new_arriaval = (isset($is_new_arriaval)) ? "1" : "0";
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
}
