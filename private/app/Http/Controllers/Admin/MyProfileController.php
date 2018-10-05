<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
//use Illuminate\Support\Facades\Route;
use Auth,
    Cookie,
    Hash,
    Input,
    Lang,
    Redirect,
    Response,
    Route,
    Session,
    URL,
    View;
use Helper;

class MyProfileController extends Controller {

    public function index(Request $request) {
        if (!Auth::check()) {
            return redirect(siteurl('admin/login'));
        }
        $userId = Auth::user()->id;
        $data = [];
        $data['userModel'] = User::find($userId);

        $value = $request->session()->pull('user_role');

        return view('admin.myprofile.index', $data);
    }

    public function validateInputs(Request $request) {
        $resp = [];

        $userModel = User::find(Auth::user()->id);

        if (isset($_GET['input_type'])) {
            if ($_GET['input_type'] == 'email') {
                if (isset($_POST['email']) && $_POST['email'] != '') {
                    if (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
                        $resp['type'] = 'error';
                        $resp['msg'] = 'Error! Provide a valid email.';
                    } else {
                        if ($_POST['email'] != $userModel->email && User::checkUserEmailExists($_POST['email'])) {
                            $resp['type'] = 'error';
                            $resp['msg'] = 'Error! Email already exits.';
                        } else {
                            $resp['type'] = 'success';
                            $resp['msg'] = '';
                        }
                    }
                } else {
                    $resp['type'] = 'error';
                    $resp['msg'] = 'Email can not be blank';
                }
            }
            if ($_GET['input_type'] == 'username') {
                if (isset($_POST['username']) && $_POST['username'] != '') {
                    if ($_POST['username'] != $userModel->username && User::checkUsernameExists($_POST['username'])) {
                        $resp['type'] = 'error';
                        $resp['msg'] = 'Error! Username already exits.';
                    } else {
                        $resp['type'] = 'success';
                        $resp['msg'] = '';
                    }
                } else {
                    $resp['type'] = 'error';
                    $resp['msg'] = 'Username can not be blank';
                }
            }
        } else {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! No direct access';
        }
        echo json_encode($resp);
        die();
    }

    public function updateMyProfile() {
        $resp = [];

        $userModel = User::find(Auth::user()->id);

        if (isset($_POST['_token'])) {
            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            if ($username == '') {
                $resp['type'] = 'error';
                $resp['input_type'] = 'username';
                $resp['msg'] = 'Username can not be blank';
            } else if ($_POST['username'] != $userModel->username && User::checkUsernameExists($username)) {
                $resp['type'] = 'error';
                $resp['input_type'] = 'username';
                $resp['msg'] = 'Error! Username already exits.';
            } else if ($email == '') {
                $resp['type'] = 'error';
                $resp['input_type'] = 'email';
                $resp['msg'] = 'Email can not be blank.';
            } else if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                $resp['type'] = 'error';
                $resp['input_type'] = 'email';
                $resp['msg'] = 'Error! Provide a valid email.';
            } else if ($email != $userModel->email && User::checkUserEmailExists($email)) {
                $resp['type'] = 'error';
                $resp['input_type'] = 'email';
                $resp['msg'] = 'Error! Email already exits.';
            } else {
                $userModel->username = trim($username);
                $userModel->email = trim($email);
                if ($password != '') {
                    $password = Hash::make(trim($password));
                    $userModel->password = $password;
                }

                if ($userModel->save()) {
                    $resp['type'] = 'success';
                    $resp['msg'] = 'Profile updated successfully.';
                } else {
                    $resp['error'] = 'error';
                    $resp['msg'] = 'Error! Unable to save data. Please try again later';
                }
            }
        } else {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! No direct access';
        }
        echo json_encode($resp);
        die();
    }

}
