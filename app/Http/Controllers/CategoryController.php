<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use function App\Http\draw_image;
use function App\Http\draw_noimage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all()->sortBy('sort_order');
        foreach ($categories as $key => $value) {

            if(!$categories[$key]->icon) {
                $categories[$key]->icon = DIR_HTTP_IMAGES."no-preview.png";
            } else {
                $categories[$key]->icon = DIR_HTTP_CATEGORY_IMAGES.$categories[$key]->category_id."/".$categories[$key]->icon;
            }
        }
        return view('category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $addText = "Add";
        $category = new Category();
        $cat = $category->getTableColumns();
        $cat = array_flip($cat);
        foreach ($cat as $key => $value) {
            $cat[$key] = "";
        }
        $cat['status'] = "checked";
        $cat['icon'] = draw_noimage(220, 150);
        $description = "Here you can add category";
        $category = "";
        return view('category.create', compact('addText', 'category', 'description', 'cat'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $name = $request->input('name');
        $sort_order = $request->input('sort_order');
        $status = $request->input('status');
        $icon = $request->file('icon');
        $category = new Category();
        $category->name = $name;
        $category->status = "0";
        if(isset($status)) {
            $category->status = "1";
        }
        if(!$sort_order) {
            $sort_order = 0;
        }
        $category->sort_order = $sort_order;
        $category->user_id = 0;
        if($category->save()) {
            if($icon != null) {
                $ext = $icon->getClientOriginalExtension();
                if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "bmp") {
                    $filename = date('Y_m_d_h_i_s') . "." . $ext;
                    $destination = DIR_WS_CATEGORY_IMAGES.$category->category_id;
                    if(!file_exists($destination)) {
                        mkdir($destination, 0777, true);
                    }
                    if($icon->move($destination, $filename)) {
                        $category = Category::find($category->category_id);
                        $category->icon = $filename;
                        $category->save();
                    }
                } else {
                    return redirect('add-category/'.$category->category_id)->with('success', 'Only PNG/JPEG/JPG/BMP is allowed.');
                }
            }
        } else {
            return redirect()->back()->with('fail', 'Error adding category.');
        }
        return redirect('add-category/'.$category->category_id)->with('success', 'Category is added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        $category = new Collection($category);
        $category = $category->all();
        if($category['status']) {
            $category['status'] = 'checked';
        } else {
            $category['status'] = "";
        }
        if($category['icon']) {
            $category['icon'] = draw_image(DIR_HTTP_CATEGORY_IMAGES.$category['category_id']."/".$category['icon'], 220, 150);
        } else {
            $category['icon'] = draw_noimage(220, 150);
        }
        $addText = "Update";
        $description = "Here you can update category";
        return view('category.create', compact('addText', 'category', 'description'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $name = $request->input('name');
        $sort_order = $request->input('sort_order');
        $status = $request->input('status');
        $icon = $request->file('icon');
        $category = Category::find($id);
        $category->name = $name;
        $category->status = "0";
        if(isset($status)) {
            $category->status = "1";
        }
        if(!$sort_order) {
            $sort_order = 0;
        }
        $category->sort_order = $sort_order;
        $category->user_id = 0;
        if($category->save()) {
            if($icon != null) {
                $ext = $icon->getClientOriginalExtension();
                if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "bmp") {
                    $filename = date('Y_m_d_h_i_s') . "." . $ext;
                    $destination = DIR_WS_CATEGORY_IMAGES.$category->category_id;
                    if(!file_exists($destination)) {
                        mkdir($destination, 0777, true);
                    }
                    if($icon->move($destination, $filename)) {
                        $category = Category::find($category->category_id);
                        unlink($destination."/".$category->icon);
                        $category->icon = $filename;
                        $category->save();
                    }
                } else {
                    return redirect('add-category/'.$category->category_id)->with('success', 'Only PNG/JPEG/JPG/BMP is allowed.');
                }
            }
        } else {
            return redirect()->back()->with('fail', 'Error updating category.');
        }
        return redirect('add-category/'.$category->category_id)->with('success', 'Category is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }

    public function getCategory($id) {
        $category = Category::find($id);
        $category = new Collection($category);
        $category = $category->all();
        echo json_encode($category);
        exit;
    }

    public function deleteCategory($id) {
        $category = Category::find($id);
        $path = DIR_WS_CATEGORY_IMAGES.$id;
        if(Category::destroy($id)) {
            if(file_exists($path."\\".$category->icon)) {
                unlink($path."\\".$category->icon);
            }
            rmdir($path);
            return redirect()->back()->with('success', 'Category is deleted successfully.');
        }
        return redirect()->back()->with('fail', 'Error deleting category.');
    }

    public function changeStatus(Request $request, $id) {
        $category = Category::find($id);
        $category->status = $request->input('status');
        if($category->save()) {
            echo "success";
        }
        exit;
    }
}
