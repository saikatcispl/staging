<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', function () {
    View::addExtension('html', 'php');
    view()->addNamespace('public', '../public_html/views');
    return view('public::index');

//    return view('welcome');
});

//============= Starting of Admin Routes =============//
Auth::routes();
Route::get('/admin/', 'Admin\LoginController@login');
Route::get('/admin/login', 'Admin\LoginController@login');
Route::post('/admin/login', 'Admin\LoginController@login')->name('admin.login');
Route::get('/admin/logout', 'Admin\LoginController@getLogout')->name('admin.logout');

Route::get('/admin/dashboard', 'Admin\DashboardController@index')->name('admin.dashboard.index');
Route::get('/admin/myprofile', 'Admin\MyProfileController@index')->name('admin.myprofile');
Route::post('/admin/myProfileValidateInputs', 'Admin\MyProfileController@validateInputs')->name('admin.myprofile.validateInputs');
Route::post('/admin/updateMyProfile', 'Admin\MyProfileController@updateMyProfile')->name('admin.myprofile.updateMyProfile');

Route::get('/admin/manageCoupons', 'Admin\CouponController@index')->name('admin.coupon.index');
Route::get('/admin/manageCoupons-data', 'Admin\CouponController@data');
Route::match(['get', 'post'], '/admin/addCoupon', 'Admin\CouponController@add')->name('admin.coupon.add');
Route::match(['get', 'post'], '/admin/updateCoupon', 'Admin\CouponController@update')->name('admin.coupon.update');
Route::any('/admin/removeCoupon', 'Admin\CouponController@remove')->name('admin.coupon.remove');
Route::get('/admin/viewCoupon', 'Admin\CouponController@view')->name('admin.coupon.view');
Route::get('/admin/generateCouponsView', 'Admin\CouponController@generateCouponsView')->name('admin.coupon.generateCouponsView');
Route::post('/admin/assignToProducts', 'Admin\CouponController@assignToProducts')->name('admin.coupon.assignToProducts');

Route::get('/admin/manageFaqs', 'Admin\FaqController@index')->name('admin.faq.index');
Route::get('/admin/manageFaqs-data', 'Admin\FaqController@data');
Route::match(['get', 'post'], '/admin/addFaq', 'Admin\FaqController@add')->name('admin.faq.add');
Route::match(['get', 'post'], '/admin/updateFaq', 'Admin\FaqController@update')->name('admin.faq.update');
Route::any('/admin/removeFaq', 'Admin\FaqController@remove')->name('admin.faq.remove');
Route::get('/admin/viewFaq', 'Admin\FaqController@view')->name('admin.faq.view');
Route::get('/admin/getFaqs', 'Admin\FaqController@getFaqs')->name('admin.faq.getFaqs');

Route::get('/admin/cms', 'Admin\CmsController@index')->name('admin.cms.index');
Route::get('/admin/manageCms', 'Admin\CmsController@index')->name('admin.cms.index');
Route::get('/admin/manageCms-data', 'Admin\CmsController@data');
Route::match(['get', 'post'], '/admin/addCms', 'Admin\CmsController@add')->name('admin.cms.add');
Route::match(['get', 'post'], '/admin/updateCms', 'Admin\CmsController@update')->name('admin.cms.update');
Route::any('/admin/removeCms', 'Admin\CmsController@remove')->name('admin.cms.remove');
Route::get('/admin/viewCms', 'Admin\CmsController@view')->name('admin.cms.view');
Route::get('/admin/getCms', 'Admin\CmsController@getCms')->name('admin.cms.getCms');

Route::get('/admin/manageProducts', 'Admin\ProductController@index')->name('admin.product.index');
Route::get('/admin/manageProducts-data', 'Admin\ProductController@data');
Route::get('/admin/addProduct', 'Admin\ProductController@add')->name('admin.product.add');
Route::post('/admin/addProductLL', 'Admin\ProductController@addProductLL')->name('admin.product.addLL');
Route::match(['get', 'post'], '/admin/updateProduct', 'Admin\ProductController@update')->name('admin.product.update');
Route::match(['get', 'post'], '/admin/manageMediaProduct', 'Admin\ProductController@manageMediaProduct')->name('admin.product.manageMediaProduct');
Route::match(['get', 'post'], '/admin/uploadMedia', 'Admin\ProductController@uploadMedia')->name('admin.product.uploadMedia');
Route::any('/admin/productSyncToLL', 'Admin\ProductController@productSyncToLL')->name('admin.product.productSyncToLL');
Route::any('/admin/productSyncFromLL', 'Admin\ProductController@productSyncFromLL')->name('admin.product.productSyncFromLL');
Route::get('/admin/manuallySyncProductFromLL', 'Admin\ProductController@manuallySyncProductFromLL')->name('admin.product.manuallySyncProductFromLL');

Route::get('/admin/manageMembershipPackage', 'Admin\MembershipPackageController@index')->name('admin.manageMembership.index');
Route::get('/admin/manageMembershipPackage-data', 'Admin\MembershipPackageController@data');
Route::get('/admin/addMembershipPackage', 'Admin\MembershipPackageController@add')->name('admin.manageMembership.add');
Route::post('/admin/addMembershipPackageLL', 'Admin\MembershipPackageController@addProductLL')->name('admin.manageMembership.addLL');
Route::match(['get', 'post'], '/admin/updateMembershipPackage', 'Admin\MembershipPackageController@update')->name('admin.manageMembership.update');
Route::match(['get', 'post'], '/admin/manageMediaMembershipPackage', 'Admin\MembershipPackageController@manageMediaProduct')->name('admin.manageMembership.manageMembershipMediaProduct');
Route::match(['get', 'post'], '/admin/uploadMembershipPackageMedia', 'Admin\MembershipPackageController@uploadMedia')->name('admin.manageMembership.uploadMedia');
Route::get('/admin/removeMembershipPackageMedia', 'Admin\MembershipPackageController@removeMembershipPackageMedia')->name('admin.manageMembership.removeMembershipPackageMedia');
Route::any('/admin/membershipPackageSyncToLL', 'Admin\MembershipPackageController@productSyncToLL')->name('admin.manageMembership.productSyncToLL');
Route::any('/admin/membershipPackageSyncFromLL', 'Admin\MembershipPackageController@productSyncFromLL')->name('admin.manageMembership.productSyncFromLL');
Route::get('/admin/manuallySyncMembershipPackageFromLL', 'Admin\MembershipPackageController@manuallySyncProductFromLL')->name('admin.manageMembership.manuallySyncProductFromLL');
Route::any('/admin/placeOrderMembershipPackage', 'Admin\MembershipPackageController@placeOrder')->name('admin.manageMembership.placeOrder');

Route::get('/admin/updateNextRebillProduct', 'Admin\SubscriptionController@updateNextRebillProduct')->name('admin.subscription.updateNextRebillProduct');
Route::get('/admin/updateRecurring', 'Admin\SubscriptionController@updateRecurring')->name('admin.subscription.updateRecurring');
Route::get('/admin/updateShippingAddress', 'Admin\SubscriptionController@updateShippingAddress')->name('admin.subscription.updateShippingAddress');
Route::get('/admin/updateBillingInfo', 'Admin\SubscriptionController@updateBillingInfo')->name('admin.subscription.updateBillingInfo');

Route::any('/admin/siteSettings', 'Admin\SiteSettingsController@index')->name('admin.siteSettings.index');
Route::any('/admin/getSiteSettings', 'Admin\SiteSettingsController@getSiteSettings')->name('admin.siteSettings.getSiteSettings');

Route::get('/admin/manageUsers', 'Admin\UserController@index')->name('admin.user.index');
Route::get('/admin/users-data', 'Admin\UserController@data');
//============= Ending of Admin Routes =============//
