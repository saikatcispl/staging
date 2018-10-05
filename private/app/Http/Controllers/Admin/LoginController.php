<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Input;
use Redirect;
use Response;
use View, Hash;

class LoginController extends Controller
{

    /**
     * Login Functionality
     */
    public function login(Request $request)
    {

        if (Auth::check()) {
            return redirect(siteurl('admin/dashboard'));
        }

        if ($request->isMethod('post')) {
            $inputs = [
                'username' => $request->input('username'),
                'password' => $request->input('password'),
            ];
            $auth = User::authorize($request, $inputs);
            return $auth;
        }

        $data = [
//            'page_scripts' => [VIEW_JS . 'auth/login.js?_=' . FILE_VERSION]
        ];

        //setCsrfCookie($request->session()->get('_token'));

        return view('admin.auth.login', $data);
    }

    /**
     * Logout Function
     */
    public function getLogout(Request $request)
    {
        Auth::logout();
//        $request->session()->reflash();
        return redirect(siteurl('admin/login'));
    }

    public function userLogin(Request $request)
    {
        $data = [];
        $inputArr = $request->all();
        
        $email = trim($inputArr['email']);
        $password = trim($inputArr['password']);
       
        if ($email != '') {
            $user = User::where('email', $email)->first();
           
            if ($user) {
                if (Hash::check($password, $user->password)) {
                    $data['type'] = 'success';
                    $data['msg'] = 'You have authenticated successfully. Welcome back.';
                    $data['user_email'] = $email;
                } else {
                    $data['type'] = 'error';
                    $data['msg'] = 'Invalid Password!';
                }
            } else {
                $data['type'] = 'error';
                $data['msg'] = 'Invalid Email or Password!';
            }
        } else {
            $data['type'] = 'error';
            $data['msg'] = 'Email is required!';
        }
        echo json_encode($data);
        die();
       // return response()->json($data);
    }

}
