<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Input;
//use Illuminate\Support\Facades\Route;
use Auth,
    Cookie,
    Hash,
    Lang,
    Redirect,
    Response,
    Route,
    Session,
    URL,
    View;
use Helper;

class CouponController extends Controller {

    public function data(Datatables $datatables) {
        return $datatables->eloquent(Coupon::query()->where('status', 1))
                        ->editColumn('action', function ($coupon) {
                            return '<a href="' . siteurl('admin/updateCoupon') . '?cid=' . $coupon->id . '" title="Edit"><i class="fa fa-edit"></i></a>'
                                    . '&nbsp;&nbsp;&nbsp;'
                                    . '<a href="javascript:void(0)" data-cid="' . $coupon->id . '" class="item-remove" title="Remove" style="color:red;"><i class="fa fa-remove"></i></a>'
                                    . '&nbsp;&nbsp;&nbsp;'
                                    . '<a class="assignToProduct" data-toggle="modal" href="#responsive" title="Assign To Product(s)" data-cid="' . $coupon->id . '"><i class="fa fa-link"></i></a>';
                        })
                        ->make(true);
    }

    public function index(Request $request) {
        if (!Auth::check()) {
            return redirect(siteurl('admin/login'));
        }
        return view('admin.coupon.index');
    }

    public function view() {
        
    }

    public function add(Request $request) {
        $data = [];
        $data['couponModel'] = $couponModel = new Coupon;
        $data['isNew'] = 'yes';

        if (isset($_POST['_token']) && $_POST['_token'] != '') {
            $inputArr = $request->all();
            unset($inputArr['_token']);

            $validator = Validator::make($inputArr, Coupon::Rules(), Coupon::$messages);
            if ($validator->fails()) {
                return redirect('admin/addCoupon')
                                ->withErrors($validator)
                                ->withInput();
//                return \Redirect::back()->withErrors($validator)->withInput();
            } else {
                $inputArr['status'] = 1;
                $inputArr['created_at'] = date('Y-m-d H:i:s');

                if (Coupon::insert($inputArr)) {
                    Session::flash('success', 'Coupon has been added successfully !!');
                } else {
                    Session::flash('error', 'Some thing went wrong!!');
                }
            }
            return Redirect::action('Admin\CouponController@index');
        }

        return view('admin.coupon.add', $data);
    }

    public function update(Request $request) {
        if (isset($_REQUEST['cid']) && $_REQUEST['cid'] != '') {
            $data = [];
            $data['couponModel'] = $couponModel = Coupon::find($_REQUEST['cid']);
            $data['isNew'] = 'no';

            if (!$couponModel) {
                return redirect(siteurl('admin/manageCoupons'));
            } else {
                if (isset($_POST['_token']) && $_POST['_token'] != '') {
                    $data = [];
                    $data['couponModel'] = $couponModel = Coupon::find($_POST['id']);
                    $data['isNew'] = 'no';
                    $inputArr = $request->all();
                    unset($inputArr['_token']);
                    $validator = Validator::make($inputArr, Coupon::Rules());

                    if ($validator->fails()) {
                        return redirect('admin/updateCoupon')
                                        ->withErrors($validator)
                                        ->withInput();
                    } else {
                        $couponModel->name = $inputArr['name'];
                        $couponModel->code = $inputArr['code'];
                        $couponModel->description = $inputArr['description'];
                        $couponModel->discount_type = $inputArr['discount_type'];
                        $couponModel->discount = $inputArr['discount'];
                        $couponModel->status = 1;
                        $couponModel->updated_at = date('Y-m-d H:i:s');

                        if ($couponModel->save()) {
                            Session::flash('success', 'Coupon has been updated successfully !!');
                        } else {
                            Session::flash('error', 'Some thing went wrong!!');
                        }
                        return Redirect::action('Admin\CouponController@index');
                    }
                }
                return view('admin.coupon.update', $data);
            }
        } else {
            return redirect(siteurl('admin/manageCoupons'));
        }
    }

    public function remove() {
        $resp = [];

        if (isset($_GET['cid']) && $_GET['cid'] != '') {
            $couponModel = Coupon::find($_GET['cid']);
            $couponModel->status = 3;

            if ($couponModel->save()) {
                $resp['type'] = 'success';
                $resp['msg'] = 'Success. Coupon deleted successfully.';
            } else {
                $resp['type'] = 'error';
                $resp['msg'] = 'Error! Unable to delete coupon.';
            }
        } else {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! No coupon Id found.';
        }
        echo json_encode($resp);
        die();
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

    

    public function generateCouponsView() {
        $resp = [];
        if (!Auth::check()) {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! You are not authorized to make this action.';
        } else {
            if (isset($_GET['cid']) && $_GET['cid'] != '') {
                $couponId = $_GET['cid'];
                $couponModel = Coupon::find($couponId);
                $resp['couponId'] = $couponModel->id;
                $resp['couponName'] = $couponModel->name;

                $assigned_products = [];
                if ($couponModel->assigned_products != '') {
                    $assigned_products = explode(",", $couponModel->assigned_products);
                }

                $productModel = Product::where('status', 1)
                        ->orderBy('id', 'desc')
                        ->get();

                $productsList = '';
                foreach ($productModel as $key => $value) {
                    if (in_array($value->id, $assigned_products)) {
                        $productsList .= "<option value='$value->id' selected>" . $value->product_name . ' (ID#' . $value->id . ')' . "</option>";
                    } else {
                        $productsList .= "<option value='$value->id'>" . $value->product_name . ' (ID#' . $value->id . ')' . "</option>";
                    }
                }
                $resp['couponDropDown'] = '<select name="productList[]" multiple class="form-control" style="height: 360px;">'
                        . $productsList
                        . '</select>';

                $resp['type'] = 'success';
                $resp['msg'] = 'Success! Products fetched successfully.';
            } else {
                $resp['type'] = 'error';
                $resp['msg'] = 'Error! No direct access.';
            }
        }

        echo json_encode($resp);
        die();
    }

    public function assignToProducts() {
        $resp = [];
        if (!Auth::check()) {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! You are not authorized to make this action.';
        } else {
            if (isset($_POST['_token'])) {
                $couponId = $_POST['coupon_id'];
                $couponModel = Coupon::find($couponId);

                $assignedProductsStr = '';
                if (!empty($_POST['productList'])) {
                    $assignedProductsStr = implode(",", $_POST['productList']);
                }
                $couponModel->assigned_products = $assignedProductsStr;

                if ($couponModel->save()) {
                    $resp['type'] = 'success';
                    $resp['msg'] = 'Success! Products assigned to the Cupon successfully.';
                } else {
                    $resp['type'] = 'error';
                    $resp['msg'] = 'Error! Unable to assign now. Please try after sometime';
                }
            } else {
                $resp['type'] = 'error';
                $resp['msg'] = 'Error! No direct access.';
            }
        }
        echo json_encode($resp);
        die();
    }

    //======= API Methods ========//

    public function getCoupons(Request $request, $status = 1, $order = 'desc', $limit = 10) {
        $data = [];
        $status = isset($_GET['status']) ? $_GET['status'] : $status;
        $order = isset($_GET['order']) ? $_GET['order'] : $order;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : $limit;

        $coupon = Coupon::where('status', $status)
                ->orderBy('id', $order)
                ->take($limit)
                ->get();

        $data['type'] = 'success';
        $data['msg'] = 'success';
        $data['data'] = $coupon;

        return response()->json($data);
    }

    public function getCoupon(Request $request) {
        $data = [];
        $couponId = isset($_GET['id']) ? $_GET['id'] : '';
        if ($couponId == '') {
            $data['type'] = 'error';
            $data['msg'] = 'Error! No Coupon ID provided.';
            $data['data'] = '';
        } else {
            $coupon = Coupon::find($couponId);
            if ($coupon) {
                $data['type'] = 'success';
                $data['msg'] = 'success';
                $data['data'] = $coupon;
            } else {
                $data['type'] = 'error';
                $data['msg'] = 'Error! No data exists for the fiven Coupon ID.';
                $data['data'] = '';
            }
        }
        return response()->json($data);
    }

}
