<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postdata = array();
        if (Cookie::get('admin_username') && Cookie::get('admin_password')) {
            $postdata['username'] = Cookie::get('admin_username');
            $postdata['password'] = Cookie::get('admin_password');
        }
        return view('login', compact('postdata'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }

    public function loginAuth(Request $request) {
        $redirect = $request->input('redirecturl');
        $admin = Admin::where("username", "=", $request->input('username'))
        ->where("password", "=", $request->input('password'))->get();
        if(count($admin) > 0) {
            $admin = new Collection($admin);
            $admin = $admin->all()[0];
            $request->session()->put('admin_login', $admin);
            $remember = $request->input('rememberme');
            if($remember == "on") {
                Cookie::queue('admin_username', $admin['username'], 500);
                Cookie::queue('admin_password', $admin['password'], 500);
            } else {
                Cookie::queue('admin_username', '');
                Cookie::queue('admin_password', '');
            }
            if($redirect) {
                return redirect($redirect);
            }
            return redirect('/dashboard');
        }
        return redirect()->back()->with('fail', 'Username or Password is incorrect');
    }

    public function getLoginAdmin(Request $request) {
        return $request->session()->get('admin_login');
    }

    public static function getLoginAdminStatic() {
        return Session::get('admin_login');
    }

    public function logout(Request $request) {
        $request->session()->forget('admin_login');
        return redirect('/');
    }
}
