<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('/admin/getSiteSettings', 'Admin\SiteSettingsController@getSiteSettings');

Route::get('getCountryList', 'Admin\UserController@getCountryList');
Route::get('getStateList', 'Admin\UserController@getStateList');

Route::get('getfaqs', 'Admin\FaqController@getFaqs');
Route::get('getfaq', 'Admin\FaqController@getFaq');

Route::get('getCoupons', 'Admin\CouponController@getCoupons');
Route::get('getCoupon', 'Admin\CouponController@getCoupon');

Route::get('getProducts', 'Admin\ProductController@getProducts');
Route::get('getProduct', 'Admin\ProductController@getProduct');

Route::get('getMembershipPackages', 'Admin\MembershipPackageController@getProducts');
Route::get('getMembershipPackage', 'Admin\MembershipPackageController@getProduct');

Route::get('/admin/getCms', 'Admin\CmsController@getCms');

Route::middleware('cors')->any('postContactForm', 'Admin\ContactController@create');
Route::middleware('cors')->any('postLoginForm', 'Admin\LoginController@userLogin');
Route::middleware('cors')->any('postSigninForm', 'Admin\UserController@postSigninForm');
