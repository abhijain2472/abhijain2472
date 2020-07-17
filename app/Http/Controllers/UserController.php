<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function App\Http\delete_directory;
use function App\Http\draw_image;
use function App\Http\draw_noimage;
use function App\Http\getDateFormat;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // $users = User::all();
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $addText = "Add";
        $description = "Here you can add customers";
        $user = new User();
        $cust = $user->getTableColumns();
        $cust['image'] = draw_noimage(220,150);
        return view('user.create', compact('addText', 'description', 'cust', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $status = $request->input('status');

        $user = new User();
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->password = $password;
        $user->status = ($status) ? "1" : "0";
        if($user->save()) {
            $image = $request->file('image');
            if($image != null) {
                $ext = $image->getClientOriginalExtension();
                if($ext !== "png" || $ext !== "jpg" || $ext !== "jpeg" || $ext !== "bmp") {
                    $filename = date('Y_m_d_h_i_s') . "." . $ext;
                    $destination = DIR_WS_USER_IMAGES.$user->id;
                    $sessionImage = $request->cookie('cropped_image_upload');
                    if(!file_exists($destination)) {
                        mkdir($destination, 0777, true);
                    }
                    if($request->cookie('cropped_image_upload')) {
                        if(copy($request->cookie('cropped_image_upload'), $destination."\\".$filename) == TRUE) {
                            unlink($request->cookie('cropped_image_upload'));
                            $user = User::find($user->id);
                            $user->image = $filename;
                            $user->save();
                        }
                    }
                } else {
                    return redirect('/add-customer/'.$user->id)->with('fail', 'Only PNG/BMP/JPEG/JPG are allowed.');
                }
            }
            return redirect('/add-customer/'.$user->id)->with('success', 'User data is added successfully.');
        }
        return redirect()->back()->with('fail', 'Error adding user data.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request ,$id) {
        $request->session()->forget('image_array');
        $addText = "Update";
        $description = "Here you can edit customers";
        $user = User::find($id);
        $user = new Collection($user);
        $user = $user->all();
        if($user['image'] && file_exists(DIR_WS_USER_IMAGES.$user['id']."\\".$user['image'])) {
            $user['image'] = draw_image(DIR_HTTP_USER_IMAGES.$user['id']."\\".$user['image'], 220, 150);
        } else {
            $user['image'] = draw_noimage(220,150);
        }
        $user['status'] = ($user['status'] == "1") ? "checked" : "";
        return view('user.create', compact('addText', 'description', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $status = $request->input('status');

        $user = User::find($id);
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        if($password) {
            $user->password = $password;
        }
        $user->status = ($status) ? "1" : "0";
        if($user->save()) {
            $image = $request->file('image');
            if($image != null) {
                $ext = $image->getClientOriginalExtension();
                if($ext !== "png" || $ext !== "jpg" || $ext !== "jpeg" || $ext !== "bmp") {
                    $filename = date('Y_m_d_h_i_s') . "." . $ext;
                    $destination = DIR_WS_USER_IMAGES.$user->id;
                    if(!file_exists($destination)) {
                        mkdir($destination, 0777, true);
                    }
                    if($request->cookie('cropped_image_upload')) {
                        if(copy($request->cookie('cropped_image_upload'), $destination."\\".$filename) == TRUE) {
                            $images = $request->session()->get('image_array');
                            foreach ($images as $key => $value) {
                                unlink($value);
                            }
                            $user = User::find($user->id);
                            $user->image = $filename;
                            $user->save();
                        }
                    }
                } else {
                    $request->session()->forget('image_array');
                    return redirect('/add-customer/'.$user->id)->with('fail', 'Only PNG/BMP/JPEG/JPG are allowed.');
                }
            }
            $request->session()->forget('image_array');
            return redirect('/add-customer/'.$user->id)->with('success', 'User data is updated successfully.');
        }
        $request->session()->forget('image_array');
        return redirect()->back()->with('fail', 'Error updating user data.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $user = User::find($id);
        if(User::destroy($id)) {
            delete_directory(DIR_WS_USER_IMAGES.$id);
            return redirect()->back()->with('success', 'User data is deleted successfully.');
        }
        return redirect()->back()->with('fail', 'Error deleting user data.');
    }

    public function getUserList(Request $request) {
        $start = $request->input('start');
        $length = $request->input('length');
        $columnlist = array('users.id', 'users.first_name', 'users.image', 'users.created_at', 'users.updated_at', 'users.status');
        $order = $request->input('order')[0];
        $users = User::all();
        $totalRec = count($users);
        $users = DB::table('users');
        if($start > 0) {
            $users->offset($start);
        }
        $users->limit($length);
        if(!empty($order)) {
            $users->orderBy($columnlist[$order['column']], $order['dir']);
        }
        $users = $users->get();
        $filterRec = count($users);
        $htmlArray = array();
        foreach ($users as $key => $value) {
            $rec = array();
            $rec[] = $key+1;
            $rec[] = $this->getCustomerDetails($value);
            if($value->image && file_exists(DIR_WS_USER_IMAGES.$value->id."\\".$value->image)) {
                $rec[] = draw_image(DIR_HTTP_USER_IMAGES.$value->id."\\".$value->image, 80, 70);
            } else {
                $rec[] = draw_noimage(80,70);
            }
            $rec[] = getDateFormat($value->created_at, true);
            $rec[] = getDateFormat($value->updated_at, true);
            $checked = ($value->status == "1") ? "checked" : "";
            $rec[] = "<div class='custom-control custom-switch'>
            <input type='checkbox' class='custom-control-input ajax-status' id='status_{$value->id}' value='on' {$checked}>
            <label class='custom-control-label' for='status_{$value->id}'></label>
            </div>";
            $rec[] = "<a href='add-customer/{$value->id}' title='Edit Category'>
            <i class='far fa-edit'></i>
            </a>
            <a href='javascript:return false;' onclick='getWarning({$value->id})' data-toggle='modal' data-target='#deleteModal' class='text-danger' title='Delete Category'>
                <i class='far fa-trash-alt'></i>
            </a>";
            $htmlArray[] = $rec;
        }
        return array(
            'data' => $htmlArray,
            'recordsTotal' => $totalRec,
            'recordsFiltered' => $filterRec,
            'draw' => $request->input('draw')
        );
    }

    private function getCustomerDetails($userData) {
        $html = "";
        if($userData) {
            $html .= "<strong>Name :</strong> ".$userData->first_name." ".$userData->last_name;
        }
        return $html;
    }

    public function changeStatus(Request $request, $id) {
        $status = $request->input('status');
        $user = User::find($id);
        $user->status = $status;
        if($user->save()) {
            return 'success';
        }
    }

    public function getCustomer($id) {
        $user = User::find($id);
        echo json_encode($user);
        exit;
    }
}
