<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductsMedia;
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
use DB;

class ProductController extends Controller {

    public function data(Datatables $datatables) {
        return $datatables->eloquent(Product::query()->where('status', 1))
                        ->editColumn('action', function ($product) {
                            return '<a href="' . siteurl('admin/updateProduct') . '?id=' . $product->id . '" title="Edit"><i class="fa fa-edit"></i></a>'
                                    . '&nbsp;&nbsp;&nbsp;'
                                    . '<a class="manuallySyncProductFromLL" href="javascript:void(0);" data-ll_product_id="' . $product->ll_product_id . '" title="Sync now from LimeLight CRM"><i class="fa fa-arrow-down"></i></a>';
                        })
                        ->make(true);
    }

    public function index(Request $request) {
        if (!Auth::check()) {
            return redirect(siteurl('admin/login'));
        }
        return view('admin.product.index');
    }

    //====== Method productSyncFromLL will create(if not exists) or update the Products from LL in Our Local DB =======//    
    public function productSyncFromLL($productID) {
        $resp = [];

        //======== Fetching LL Credentials from ENV File =========//
        $LLCRM_Instance = env('LLCRM_Instance');
        $LLCRM_APIUsername = env('LLCRM_APIUsername');
        $LLCRM_APIPassword = env('LLCRM_APIPassword');

        $data = [
            'username' => $LLCRM_APIUsername,
            'password' => $LLCRM_APIPassword,
        ];
//        $LLProductID = 1968;
        $LLProductID = $productID;
        $productModel = Product::where('ll_product_id', $LLProductID)->first();

        //====== Creating Product View Url =======// 
        $productViewUrl = 'https://' . $LLCRM_Instance . '.limelightcrm.com/admin/membership.php?username=' . $LLCRM_APIUsername . '&password=' . $LLCRM_APIPassword . '&method=product_index&product_id=' . $LLProductID . '&return_format=json';

        //====== Fetching The Product Details =======//
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $productViewUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl_response = curl_exec($ch);
        parse_str($curl_response, $response_output);
        curl_close($ch);
        $productDataArr = $response_output;

        if ($productDataArr['response_code'] == 100) {
            unset($productDataArr['response_code']);
            $productDataArr['ll_product_id'] = $LLProductID;
            $productDataArr['status'] = 1;
            $productDataArr['created_at'] = date('Y-m-d H:i:s');

            if ($productModel) {
                $productModel->product_name = $productDataArr['product_name'];
                $productModel->product_description = $productDataArr['product_description'];
                $productModel->product_sku = $productDataArr['product_sku'];
                $productModel->product_price = $productDataArr['product_price'];
                $productModel->product_category_name = $productDataArr['product_category_name'];
                $productModel->vertical_name = $productDataArr['vertical_name'];
                $productModel->product_is_trial = $productDataArr['product_is_trial'];
                $productModel->product_is_shippable = $productDataArr['product_is_shippable'];
                $productModel->product_rebill_product = $productDataArr['product_rebill_product'];
                $productModel->product_rebill_days = $productDataArr['product_rebill_days'];
                $productModel->product_max_quantity = $productDataArr['product_max_quantity'];
                $productModel->preserve_recurring_quantity = $productDataArr['preserve_recurring_quantity'];
                $productModel->subscription_type = $productDataArr['subscription_type'];
                $productModel->subscription_week = $productDataArr['subscription_week'] ? $productDataArr['subscription_week'] : NULL;
                $productModel->subscription_day = $productDataArr['subscription_day'] ? $productDataArr['subscription_day'] : NULL;
                $productModel->cost_of_goods_sold = $productDataArr['cost_of_goods_sold'] ? $productDataArr['cost_of_goods_sold'] : NULL;
                $productModel->updated_at = date('Y-m-d H:i:s');
                if ($productModel->save()) {
                    $resp['type'] = 'success';
                    $resp['msg'] = 'Success. Product updated successfully.';
                } else {
                    $resp['type'] = 'error';
                    $resp['msg'] = 'Error! Unable to update product now. Please try after sometime';
                }
            } else {
                if ($productDataArr['subscription_week'] == '') {
                    unset($productDataArr['subscription_week']);
                }
                if ($productDataArr['subscription_day'] == '') {
                    unset($productDataArr['subscription_day']);
                }
                if ($productDataArr['cost_of_goods_sold'] == '') {
                    unset($productDataArr['cost_of_goods_sold']);
                }
                if (Product::insert($productDataArr)) {
                    $resp['type'] = 'success';
                    $resp['msg'] = 'Success. Product added successfully.';
                } else {
                    $resp['type'] = 'error';
                    $resp['msg'] = 'Error! Unable to add product now. Please try after sometime';
                }
            }
        } else {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! Product ID:' . $LLProductID . '(Response Code:' . $productDataArr['response_code'] . ')';
        }

        echo json_encode($resp);
        die();
    }

    public function manuallySyncProductFromLL() {
        $resp = [];
        if (!Auth::check()) {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! You are not authorized to make this action.';
        } else {
            if (isset($_GET['ll_product_id']) && $_GET['ll_product_id'] != '') {
                $this->productSyncFromLL($_GET['ll_product_id']);

                $resp['type'] = 'success';
                $resp['msg'] = 'Success! Product has been synced from LimeLight successfully.';
                Session::flash('success', 'Product has been synced from LimeLight successfully.');
            } else {
                $resp['type'] = 'error';
                $resp['msg'] = 'Error! No direct access.';
            }
        }
        echo json_encode($resp);
        die();
    }

    public function add(Request $request) {
        $data = [];
        $data['productModel'] = $productModel = new Product;
        return view('admin.product.add', $data);
    }

    public function addProductLL(Request $request) {
        $resp = [];
        if (!Auth::check()) {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! You are not authorized to make this action.';
        } else {
            if (isset($_POST['_token']) && $_POST['_token'] != '') {
                $inputArr = $request->all();
                unset($inputArr['_token']);

                $productModel = new Product;
                $product = Product::where('ll_product_id', $inputArr['ll_product_id'])->first();

                //=== Checking if the Same LL Product ID present in our DB or not ===//
                if ($product) {
                    $resp['type'] = 'error';
                    $resp['msg'] = 'Error! This Limelight Product ID is already exists.';
                } else {
                    $inputArr['status'] = 1;
                    $inputArr['created_at'] = date('Y-m-d H:i:s');

                    if (Product::insert($inputArr)) {
                        $this->productSyncFromLL($inputArr['ll_product_id']);
                        $resp['type'] = 'success';
                        $resp['msg'] = 'Success. Product has been added successfully.';
                        Session::flash('success', 'Product has been added successfully !!');
                    } else {
                        $resp['type'] = 'error';
                        $resp['msg'] = 'Error.! Something went wrong!!';
                        Session::flash('error', 'Something went wrong!!');
                    }
                }
            } else {
                $resp['type'] = 'error';
                $resp['msg'] = 'Error! No direct access.';
            }
        }
        echo json_encode($resp);
        die();
    }

    public function update(Request $request) {
        if (!Auth::check()) {
            return redirect(siteurl('admin/login'));
        }
        $data = [];
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $productID = $_GET['id'];
            $data['productModel'] = $productModel = Product::find($productID);

            if (!$productModel) {
                return redirect(siteurl('admin/manageProducts'));
            }

            if (isset($_POST['_token']) && $_POST['_token'] != '') {
                $inputArr = $request->all();
                unset($inputArr['_token']);
                $validator = Validator::make($inputArr, Product::Rules());

                if ($validator->fails()) {
                    return redirect('admin/updateProduct')
                                    ->withErrors($validator)
                                    ->withInput();
                } else {
                    $productModel->product_name = $inputArr['product_name'];
                    $productModel->product_sku = $inputArr['product_sku'];
                    $productModel->product_price = $inputArr['product_price'];
                    $productModel->product_max_quantity = $inputArr['product_max_quantity'];
                    $productModel->product_description = $inputArr['product_description'];

                    if (isset($inputArr['priority_pricing_enable'])) {
                        $productModel->priority_pricing_enable = 1;
                        $productModel->priority_pricing_amount = $inputArr['priority_pricing_amount'] == '' ? 0 : $inputArr['priority_pricing_amount'];
                    }
                    $productModel->updated_at = date('Y-m-d h:i:s');

                    if ($productModel->save()) {
                        $this->productSyncToLL($productModel->ll_product_id);
                        Session::flash('success', 'Product has been updated successfully !!');
                    } else {
                        Session::flash('error', 'Some thing went wrong!!');
                    }
                    return Redirect::action('Admin\ProductController@index');
                }
            }
        } else {
            return redirect(siteurl('admin/manageProducts'));
        }
        return view('admin.product.update', $data);
    }

    public function productSyncToLL($productID) {
        $resp = [];

        //======== Fetching LL Credentials from ENV File =========//
        $LLCRM_Instance = env('LLCRM_Instance');
        $LLCRM_APIUsername = env('LLCRM_APIUsername');
        $LLCRM_APIPassword = env('LLCRM_APIPassword');

//        $LLProductID = 1967;
        $LLProductID = $productID;
        $productModel = Product::where('ll_product_id', $LLProductID)->first();

        //====== Creating Product Update Url =======//

        $product_param = $LLProductID . ',' . $LLProductID . ',' . $LLProductID . ',' . $LLProductID . ',' . $LLProductID;
        $actions_param = 'product_name,product_sku,product_price,max_quantity,product_description';
        $value_param = urlencode($productModel->product_name) . ',' . urlencode($productModel->product_sku) . ',' . urlencode($productModel->product_price) . ',' . $productModel->product_max_quantity . ',' . urlencode($productModel->product_description);
        $productUpdateUrl = 'https://' . $LLCRM_Instance . '.limelightcrm.com/admin/membership.php?username=' . $LLCRM_APIUsername . '&password=' . $LLCRM_APIPassword . '&method=product_update&product_ids=' . $product_param . '&actions=' . $actions_param . '&values=' . $value_param;

        //====== Updating The Product Details to LimeLight CRM =======//
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $productUpdateUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl_response = curl_exec($ch);
        parse_str($curl_response, $response_output);
        curl_close($ch);

        $resp['type'] = 'success';

        if (in_array(100, explode(",", $response_output['response_code']))) {
            $resp['msg'] = 'Success! Product has been synced to LimeLight successfully.';
        } else {
            $resp['msg'] = 'No new changes has been made';
        }

        echo json_encode($resp);
    }

    public function manageMediaProduct() {
        if (!Auth::check()) {
            return redirect(siteurl('admin/login'));
        }
        $data = [];

        if (isset($_GET['id']) && $_GET['id'] != '') {
            $productID = $_GET['id'];
            $data['productModel'] = $productModel = Product::find($productID);

            if (!$productModel) {
                return redirect(siteurl('admin/manageProducts'));
            }
        } else {
            return redirect(siteurl('admin/manageMedia'));
        }
        return view('admin.product.manageMedia', $data);
    }

    public function uploadMedia() {
        $resp = [];
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $productID = $_GET['id'];
            $productModel = Product::find($productID);

            $productsMediaModel = new ProductsMedia;
            $fileType = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            $fileName = $_FILES['file']['name'] ? 'PRO-' . $productModel->ll_product_id . date('s') . rand(2, 3).$fileType : '';

            $productsMediaModel->product_id = $productModel->id;
            if ($fileType == 'jpg' || $fileType == 'jpeg' || $fileType == 'png' || $fileType == 'gif') {
                $productsMediaModel->image = $fileName;
                $target_file = $_SERVER['DOCUMENT_ROOT'] . '/backend/images/products/';
            } else {
                $productsMediaModel->document_file = $fileName;
                $target_file = $_SERVER['DOCUMENT_ROOT'] . '/backend/docs/products/';
            }
            $productsMediaModel->created_at = date('Y-m-d h:i:s');
            $productsMediaModel->save();
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file . $fileName)) {
                $resp['type'] = 'success';
                $resp['msg'] = 'File uploded successfully';
            } else {
                $resp['type'] = 'error';
                $resp['msg'] = 'Sorry, there was an error uploading your file.';
            }
        } else {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! No Product ID found.';
        }
        echo json_encode($resp);
    }

    //======= API Methods ========//

    public function getProducts(Request $request, $status = 1, $order = 'desc', $limit = 3) {
        $data = [];
        $status = isset($_GET['status']) ? $_GET['status'] : $status;
        $order = isset($_GET['order']) ? $_GET['order'] : $order;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : $limit;
        $media = isset($_GET['media']) && $_GET['media'] == 1 ? 1 : 0;

        $data['type'] = 'success';
        $data['msg'] = 'success';
        if ($media == 1) {
            $products = Product::where('status', $status)
                    ->orderBy('id', $order)
                    ->with('getMedia')
                    ->paginate($limit);
        } else {
            $products = Product::where('status', $status)
                    ->orderBy('id', $order)
                    ->paginate($limit);
        }
        $data['data'] = $products;
        return response()->json($data);
    }

    public function getProduct(Request $request) {
        $data = [];
        $productId = isset($_GET['id']) ? $_GET['id'] : '';
        if ($productId == '') {
            $data['type'] = 'error';
            $data['msg'] = 'Error! No Product ID provided.';
            $data['data'] = '';
        } else {
            $product = Product::find($productId);
            $product['get_media'] = $get_media = $product->getMedia;
            $product['media'] = count($get_media) > 0 ? true : false;
            if ($product) {
                $data['type'] = 'success';
                $data['msg'] = 'success';
                $data['data'] = $product;
            } else {
                $data['type'] = 'error';
                $data['msg'] = 'Error! No data exists for the fiven Product ID.';
                $data['data'] = '';
            }
        }
        return response()->json($data);
    }

}
