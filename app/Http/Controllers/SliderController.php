<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use function App\Http\draw_image;
use function App\Http\draw_noimage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::all();
        foreach ($sliders as $key => $value) {
            if($value['slider_image']) {
                $sliders[$key]['slider_image'] = draw_image(DIR_HTTP_SLIDER_IMAGES.$value['slider_id']."/".$value['slider_image'], 80, 70);
            } else {
                $sliders[$key]['slider_image'] = draw_noimage(80, 70);
            }
        }
        return view('slider.index', compact('sliders'));
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
        $slider = new Slider();
        $slid = $slider->getTableColumns();
        $slid = array_flip($slid);
        foreach ($slid as $key => $value) {
            $slid[$key] = "";
        }
        $slid['status'] = "checked";
        $slid['slider_image'] = draw_noimage(220, 150);
        return view('slider.create', compact('addText', 'description', 'slid'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $slider_title = $request->input('slider_title');
        $slider_message = $request->input('slider_message');
        $sort_order = $request->input('sort_order');
        $status = $request->input('status');

        $slider = new Slider();
        $slider->slider_title = $slider_title;
        $slider->slider_message = $slider_message;
        $slider->sort_order = 0;
        if($sort_order) {
            $slider->sort_order = $sort_order;
        }
        $slider->status = ($status) ? "1" : "0";
        $slider->slider_title = $slider_title;
        if($slider->save()) {
            $slider_image = $request->file('slider_image');
            if($slider_image != null) {
                $ext = $slider_image->getClientOriginalExtension();
                if($ext == "png" || $ext == "bmp" || $ext == "jpeg" || $ext == "jpg") {
                    $filename = date("Y_m_d_h_i_s") . "." . $ext;
                    $destination = DIR_WS_SLIDER_IMAGES . $slider->slider_id;
                    if(!file_exists($destination)) {
                        mkdir($destination, 0777, true);
                    }
                    if($slider_image->move($destination, $filename)) {
                        $slider = Slider::find($slider->slider_id);
                        $slider->slider_image = $filename;
                        $slider->save();
                    }
                } else {
                    return redirect('/add-slider/'.$slider->slider_id)->with('success', 'Only PNG/JPEG/BMP/JPG is allowed.');
                }
            }
        } else {
            return redirect()->back()->with('fail', 'Error adding slider.');
        }
        return redirect('/add-slider/'.$slider->slider_id)->with('success', 'Slider is added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $addText = "Update";
        $description = "Here you can edit products.";
        $slider = new Slider();
        $slid = $slider->getTableColumns();
        $slid = array_flip($slid);
        foreach ($slid as $key => $value) {
            $slid[$key] = "";
        }
        $slid['status'] = "checked";
        $slid['slider_image'] = draw_noimage(220, 150);
        $slider = Slider::find($id);
        $slider = new Collection($slider);
        $slider = $slider->all();
        if($slider['slider_image']) {
            $slider['slider_image'] = draw_image(DIR_HTTP_SLIDER_IMAGES.$slider['slider_id']."/".$slider['slider_image'], 220, 150);
        } else {
            $slider['slider_image'] = draw_noimage(220, 150);
        }
        $slider['status'] = ($slider['status'] == "1") ? "checked" : "";
        return view('slider.create', compact('addText', 'description', 'slid', 'slider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $slider_title = $request->input('slider_title');
        $slider_message = $request->input('slider_message');
        $sort_order = $request->input('sort_order');
        $status = $request->input('status');

        $slider = Slider::find($id);
        $slider->slider_title = $slider_title;
        $slider->slider_message = $slider_message;
        $slider->sort_order = 0;
        if($sort_order) {
            $slider->sort_order = $sort_order;
        }
        $slider->status = ($status) ? "1" : "0";
        $slider->slider_title = $slider_title;
        if($slider->save()) {
            $slider_image = $request->file('slider_image');
            if($slider_image != null) {
                $ext = $slider_image->getClientOriginalExtension();
                if($ext == "png" || $ext == "bmp" || $ext == "jpeg" || $ext == "jpg") {
                    $filename = date("Y_m_d_h_i_s") . "." . $ext;
                    $destination = DIR_WS_SLIDER_IMAGES . $slider->slider_id;
                    if(!file_exists($destination)) {
                        mkdir($destination, 0777, true);
                    }
                    if($slider_image->move($destination, $filename)) {
                        $slider = Slider::find($slider->slider_id);
                        unlink($destination."/".$slider->slider_image);
                        $slider->slider_image = $filename;
                        $slider->save();
                    }
                } else {
                    return redirect()->back()->with('success', 'Only PNG/JPEG/BMP/JPG is allowed.');
                }
            }
        } else {
            return redirect()->back()->with('fail', 'Error updating slider.');
        }
        return redirect()->back()->with('success', 'Slider is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        //
    }

    public function getSlider($id)  {
        $slider = Slider::find($id);
        echo json_encode($slider);
        exit;
    }

    public function deleteSlider($id) {
        $slider = Slider::find($id);
        $path = DIR_WS_SLIDER_IMAGES.$id;
        if(Slider::destroy($id)) {
            unlink($path."/".$slider->slider_image);
            rmdir($path);
            return redirect()->back()->with('success', 'Slider is deleted successfully.');
        }
        return redirect()->back()->with('fail', 'Error deleting slider.');
    }

    public function changeStatus(Request $request, $id) {
        $slider = Slider::find($id);
        $slider->status = $request->input('status');
        if($slider->save()) {
            echo "success";
        }
        exit;
    }
}
