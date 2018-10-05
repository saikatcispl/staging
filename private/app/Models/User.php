<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Http\Request;
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
    View,
    Crypt,
    Validator;
use DB,
    DateTime,
    Datatables;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract {

    use Authenticatable,
        Authorizable,
        CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = ['first_name', 'last_name', 'phone', 'username', 'email', 'password', 'active', 'deleted'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];
    protected $primaryKey = 'id';

    public function username() {
        return 'username';
    }

    public static function authorize($request, $inputs) {
        /* if either email or password is blank, then return invalid */
        if (empty($inputs['username']) || empty($inputs['password'])) {
            return Response::json(['success' => false, 'message' => Lang::get('messages.AUTH.FAILURE'), 'redir' => '']); // invalid credentials
        }

        /* check for provided credentials in DB */
//        $user = DB::table('users')
//                ->select('id', 'email', 'password')
//                ->where('username', trim($inputs['username']))
//                ->where('role_id', 1)
//                ->where('deleted', 0)
//                ->orderBy('id', 'asc')
//                ->first();

        $user = DB::table('users')
                ->whereRaw('username="' . trim($inputs['username']) . '"')
                ->first();

        if (!empty($user->id)) {
            if (($user->role_id != '1') AND ( $user->role_id != '2')) {
                return Response::json(['success' => false, 'message' => Lang::get('messages.AUTH.NOT_PERMITTED'), 'redir' => '']); // Failed Attempt
            }

            /* if password does not match */
            if (!Hash::check($inputs['password'], $user->password)) {
                return Response::json(['success' => false, 'message' => Lang::get('messages.AUTH.FAILED_ATTEMPT', ['LINKURL' => siteurl('forgot-password')]), 'redir' => '']); // Failed Attempt
            } else {
//if password matches, account is not locked, then create session
                if (Auth::attempt([
                            'email' => trim($user->email),
                            'password' => trim($inputs['password'])
                                ], !empty($inputs['remember']) ? true : false)) {
                    $request->session()->push('user_role', $user->role_id);
                    $request->session()->push('username', $user->username);
                    return Response::json(['success' => true, 'message' => '', 'redir' => siteurl('admin/dashboard')]);
                }
            }
        } else {
            return Response::json(['success' => false, 'message' => Lang::get('messages.AUTH.FAILURE'), 'redir' => '']);
        }
    }

    public static function checkUserEmailExists($email) {
        $record = DB::table('users')
                ->where('email', trim($email))
                ->count();
        return $record;
    }
    
    public static function checkUsernameExists($username) {
        $record = DB::table('users')
                ->where('username', trim($username))
                ->count();
        return $record;
    }
    
//
//    public static function register($inputs) {
//        /* if any field is blank, then return invalid */
//        $is_email = '0';
//        $is_slug_exists = '0';
//        if (!empty($inputs['email'])) {
//            $is_email = self::checkUserEmailExists($inputs['email']);
//        }
//        if (!empty($inputs['business_slug'])) {
//            $is_slug_exists = self::checkSlug($inputs['business_slug']);
//        }
////        if (empty($inputs['first_name']) || empty($inputs['last_name']) || empty($inputs['email']) || empty($inputs['phone']) || empty($inputs['password']) || empty($inputs['address']) || empty($inputs['city']) || empty($inputs['country']) || empty($inputs['business_name']) || empty($inputs['business_email']) || empty($inputs['business_phone']) || empty($inputs['business_address']) || empty($inputs['business_slug'])) {
////            return json_encode(["response" => "FAIL", "message" => "Please fill all the fields"]); // invalid credentials
////        } else
//        if ($is_email > '0') {
//            return json_encode(["response" => "FAIL", "message" => "Email is already exists. Please try with another."]);
//        } else if ($is_slug_exists > '0') {
//            return json_encode(["response" => "FAIL", "message" => "Slug is already provided to another user. Please try again."]);
//        } else {
//            $resetNonce = Crypt::encrypt(microtime());
//            $inputs['verify_account'] = $resetNonce;
//            $verify_account_link = siteurl('account-verify/' . $resetNonce);
//            DB::table("users")->insertGetId($inputs);
//            $to = trim($inputs['email']);
//            $subject = "SiteBuilder - Account Verification Email";
//            $message = "
//                        <html>
//                        <head>
//                        </head>
//                        <body>
//                        <p>Dear " . trim($inputs['first_name']) . ",</p>
//                        <p>Please click on the link below to verify your account. Click here to <a href=" . $verify_account_link . " target='_blank'>verify your account.</a></p>
//                        </body>
//                        </html>
//                        ";
//
//            $headers = "MIME-Version: 1.0" . "\r\n";
//            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
//            $headers .= 'From: <siteazon@gmail.com>' . "\r\n";
//            mail($to, $subject, $message, $headers);
//            return json_encode(["response" => "SUCCESS", "message" => "Thanks for registering with us. We have sent you the verification link shortly."]);
//        }
//    }
//
//    public static function getUserById($userID) {
//        $record = DB::table('users')
//                ->select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.business_name', 'users.created_at')
//                ->where('users.id', $userID)
//                ->where('users.active', '1')
//                ->first();
//        return $record;
//    }
//
//    public static function resetPasswordRequest($request) {
//        if (empty($request->email)) {
//            return Response::json(['success' => false, 'message' => Lang::get('messages.PASSWORD_RESET.INVALID_INPUTS'), 'redir' => '']);
//        }
//
//        $user = DB::table('users')
//                ->select('id', 'first_name', 'last_name', 'email', 'password_last_reset_at', 'password_reset_nonce', 'password_reset_nonce_created_at')
//                ->where('email', trim($request->email))
//                ->where('active', 1)
//                ->where('deleted', 0)
//                ->orderBy('id', 'asc')
//                ->first();
//
//        if (empty($user->id)) {
//            return Response::json(['success' => false, 'message' => Lang::get('messages.PASSWORD_RESET.INVALID_EMAIL'), 'redir' => '']);
//        }
//
//        $resetNonce = Crypt::encrypt(microtime());
//
//        $updateData = [
//            'password_reset_nonce' => $resetNonce,
//            'password_reset_nonce_created_at' => CURR_DATE_TIME
//        ];
//
//        if (!DB::table('users')->where('id', $user->id)->update($updateData)) {
//            return Response::json(['success' => false, 'message' => Lang::get('messages.PASSWORD_RESET.UPDATE_FAILURE'), 'redir' => '']);
//        }
//
//        try {
//            $reset_link = siteurl('reset-password/' . $resetNonce);
//            $to = "amit.verma@codeclouds.in";
//            $subject = "SiteBuilder Account Recovery";
//            $message = "
//                        <html>
//                        <head>
//                        </head>
//                        <body>
//                        <p>Dear " . trim($user->first_name) . ",</p>
//                        <p>Please click on the link below to reset your password. Click here to <a href=" . $reset_link . " target='_blank'>reset your password</a></p>
//                        </body>
//                        </html>
//                        ";
//
//            $headers = "MIME-Version: 1.0" . "\r\n";
//            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
//            $headers .= 'From: <siteazon@gmail.com>' . "\r\n";
//            mail($to, $subject, $message, $headers);
//        } catch (Exception $ex) {
//            
//        }
//
//        return Response::json(['success' => true, 'message' => Lang::get('messages.PASSWORD_RESET.SUCCESS'), 'redir' => siteurl('login')]);
//    }

    public static function validateToken($resetNonce) {
        $invalidMsg = Lang::get('messages.PASSWORD_RESET.INVALID_TOKEN', ['LINKURL' => siteurl('forgot-password')]);

        if (empty($resetNonce)) {
            Session::flash('invalid_reset_token_message', $invalidMsg);
            return false;
        }

        $user = DB::table('users')
                ->select('id', 'first_name', 'last_name', 'email', DB::raw('TIMESTAMPDIFF(HOUR, password_reset_nonce_created_at, \'' . CURR_DATE_TIME . '\') AS nonce_elapsed'))
                ->where('password_reset_nonce', $resetNonce)
                ->where('active', 1)
                ->where('deleted', 0)
                ->orderBy('id', 'asc')
                ->first();

        if (empty($user->id) || $user->nonce_elapsed > PASSWORD_RESET_REQUEST_TOKEN_EXPIRATION) {
            Session::flash('invalid_reset_token_message', $invalidMsg);
            return false;
        }

        return $user;
    }

//    public static function resetPassword($inputs) {
//        $invalidMsg = Lang::get('messages.PASSWORD_RESET.INVALID_TOKEN', ['LINKURL' => siteurl('forgot-password')]);
//
//        if (empty($inputs['reset_token'])) {
//            return Response::json(['success' => false, 'message' => $invalidMsg, 'redir' => '']);
//        }
//
//        if (empty($inputs['password'])) {
//            return Response::json(['success' => false, 'message' => Lang::get('messages.PASSWORD_RESET.NO_PASSWORD'), 'redir' => '']);
//        }
//
//        $user = DB::table('users')
//                ->select('id', 'first_name', 'email', 'password', DB::raw('TIMESTAMPDIFF(HOUR, password_reset_nonce_created_at, \'' . CURR_DATE_TIME . '\') AS nonce_elapsed'))
//                ->where('password_reset_nonce', $inputs['reset_token'])
//                ->where('active', 1)
//                ->where('deleted', 0)
//                ->orderBy('id', 'asc')
//                ->first();
//
//        if (empty($user->id) || $user->nonce_elapsed > PASSWORD_RESET_REQUEST_TOKEN_EXPIRATION) {
//            return Response::json(['success' => false, 'message' => $invalidMsg, 'redir' => '']);
//        }
//
//        if (Hash::check($inputs['password'], $user->password)) {
//            return Response::json(['success' => false, 'message' => Lang::get('messages.PASSWORD_RESET.PREV_PASSWORD_ERROR'), 'redir' => '']);
//        }
//
//        $updateData = [
//            'password' => Hash::make(trim($inputs['password'])),
//            'password_last_reset_at' => CURR_DATE_TIME,
//            'password_reset_nonce' => NULL,
//            'password_reset_nonce_created_at' => NULL,
////            'is_first_login' => 0,
//        ];
//
//        $reset = DB::table('users')
//                ->where('id', $user->id)
//                ->update($updateData);
//        return Response::json(['success' => true, 'message' => Lang::get('messages.PASSWORD_RESET.RESET_SUCCESS'), 'redir' => siteurl('login')]);
////        if ($reset) {
////            return Response::json(['success' => true, 'message' => Lang::get('messages.PASSWORD_RESET.RESET_SUCCESS'), 'redir' => siteurl('login')]);
////        }
//    }
//
//    public static function checkSlug($slug_value) {
//        $result = DB::table('users')
//                ->select('business_slug')
//                ->where('business_slug', $slug_value)
//                ->count();
//        return $result;
//    }
//
//    public static function validateAccountVerifyToken($resetNonce) {
//        $user = DB::table('users')
//                ->select('id')
//                ->where('verify_account', $resetNonce)
//                ->where('active', 0)
//                ->where('deleted', 0)
//                ->first();
//
//        if (!empty($user->id)) {
//            $user_data = ['active' => 1];
//            Session::flash('valid_account_token_message', 'Your account has been validate successfully. Please login below with your credentials.');
//            DB::table("users")->where('id', $user->id)->update($user_data);
//            return Response::json(['success' => true, 'message' => 'Account verify successfully.']);
//        } else {
//            Session::flash('invalid_account_token_message', 'You have already validate your account. Please login below with your credentials or contact with the Administrator.');
//            return Response::json(['success' => false, 'message' => 'Something went wrong.']);
//        }
//    }
}


