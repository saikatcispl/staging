<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPackage;
use App\Models\MembershipPackageMedia;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Redirect;
use Response;
use Session;
use View;
use Yajra\Datatables\Datatables;

class MembershipPackageController extends Controller
{
    public function data(Datatables $datatables)
    {
        return $datatables->eloquent(MembershipPackage::query()->where('status', 1)->orderBy('id', 'DESC'))
            ->editColumn('action', function ($membershipPackage) {
                return '<a href="' . siteurl('admin/updateMembershipPackage') . '?id=' . $membershipPackage->id . '" title="Edit"><i class="fa fa-edit"></i></a>'
                . '&nbsp;&nbsp;&nbsp;'
                . '<a class="manuallySyncProductFromLL" href="javascript:void(0);" data-ll_product_id="' . $membershipPackage->ll_product_id . '" title="Sync now from LimeLight CRM"><i class="fa fa-arrow-down"></i></a>';
            })
            ->make(true);
    }

    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect(siteurl('admin/login'));
        }
        return view('admin.membershipPackage.index');
    }

    //====== Method productSyncFromLL will create(if not exists) or update the Products from LL in Our Local DB =======//
    public function productSyncFromLL($productID)
    {
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
        $productModel = MembershipPackage::where('ll_product_id', $LLProductID)->first();

        //====== Creating Product View Url =======//
        $productViewUrl = 'https://' . $LLCRM_Instance . '.limelightcrm.com/admin/membership.php?username=' . $LLCRM_APIUsername . '&password=' . $LLCRM_APIPassword . '&method=product_index&product_id=' . $LLProductID . '&return_format=json';

        //====== Fetching The Product Details =======//
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $productViewUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
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
                $productModel->subscription_week = $productDataArr['subscription_week'] ? $productDataArr['subscription_week'] : null;
                $productModel->subscription_day = $productDataArr['subscription_day'] ? $productDataArr['subscription_day'] : null;
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
                if (MembershipPackage::insert($productDataArr)) {
                    $resp['type'] = 'success';
                    $resp['msg'] = 'Success. Membership Package added successfully.';
                } else {
                    $resp['type'] = 'error';
                    $resp['msg'] = 'Error! Unable to add Membership Package now. Please try after sometime';
                }
            }
        } else {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! Product ID:' . $LLProductID . '(Response Code:' . $productDataArr['response_code'] . ')';
            $productModel->status = 3;
            $productModel->save();
        }

        echo json_encode($resp);
        die();
    }

    public function manuallySyncProductFromLL()
    {
        $resp = [];
        if (!Auth::check()) {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! You are not authorized to make this action.';
        } else {
            if (isset($_GET['ll_product_id']) && $_GET['ll_product_id'] != '') {
                $this->productSyncFromLL($_GET['ll_product_id']);

                $resp['type'] = 'success';
                $resp['msg'] = 'Success! Membership Package has been synced from LimeLight successfully.';
                Session::flash('success', 'Membership Package has been synced from LimeLight successfully.');
            } else {
                $resp['type'] = 'error';
                $resp['msg'] = 'Error! No direct access.';
            }
        }
        echo json_encode($resp);
        die();
    }

    public function add(Request $request)
    {
        $data = [];
        $data['productModel'] = $productModel = new MembershipPackage;
        return view('admin.membershipPackage.add', $data);
    }

    public function addProductLL(Request $request)
    {
        $resp = [];
        if (!Auth::check()) {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! You are not authorized to make this action.';
        } else {
            if (isset($_POST['_token']) && $_POST['_token'] != '') {
                $inputArr = $request->all();
                unset($inputArr['_token']);

                $productModel = new MembershipPackage;
                $product = MembershipPackage::where('ll_product_id', $inputArr['ll_product_id'])->first();

                //=== Checking if the Same LL Product ID present in our DB or not ===//
                if ($product) {
                    $resp['type'] = 'error';
                    $resp['msg'] = 'Error! This Limelight Membership Package ID is already exists.';
                } else {
                    $inputArr['status'] = 1;
                    $inputArr['created_at'] = date('Y-m-d H:i:s');

                    if (MembershipPackage::insert($inputArr)) {
                        $this->productSyncFromLL($inputArr['ll_product_id']);
                        $resp['type'] = 'success';
                        $resp['msg'] = 'Success. Membership Package has been added successfully.';
                        Session::flash('success', 'Membership Package has been added successfully !!');
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

    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect(siteurl('admin/login'));
        }
        $data = [];
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $productID = $_GET['id'];
            $data['productModel'] = $productModel = MembershipPackage::find($productID);

            if (!$productModel) {
                return redirect(siteurl('admin/manageMembershipPackage'));
            }

            if (isset($_POST['_token']) && $_POST['_token'] != '') {
                $inputArr = $request->all();

                unset($inputArr['_token']);
                $validator = Validator::make($inputArr, MembershipPackage::Rules());

                if ($validator->fails()) {
                    // $validator->errors()
                    return redirect('admin/updateMembershipPackage')
                        ->withErrors($validator)
                        ->withInput();
                } else {

                    $productModel->product_name = $inputArr['product_name'];
                    $productModel->product_sku = $inputArr['product_sku'];
                    $productModel->product_price = $inputArr['product_price'];
                    $productModel->product_max_quantity = 1;
                    $productModel->product_description = $inputArr['product_description'];
                    $productModel->updated_at = date('Y-m-d h:i:s');

                    if ($productModel->save()) {
                        $this->productSyncToLL($productModel->ll_product_id);
                        Session::flash('success', 'Membership Package has been updated successfully !!');
                    } else {
                        Session::flash('error', 'Some thing went wrong!!');
                    }
                    return Redirect::action('Admin\MembershipPackageController@index');
                }
            }
        } else {
            return redirect(siteurl('admin/manageMembershipPackage'));
        }
        return view('admin.membershipPackage.update', $data);
    }

    public function productSyncToLL($productID)
    {
        $resp = [];

        //======== Fetching LL Credentials from ENV File =========//
        $LLCRM_Instance = env('LLCRM_Instance');
        $LLCRM_APIUsername = env('LLCRM_APIUsername');
        $LLCRM_APIPassword = env('LLCRM_APIPassword');

//        $LLProductID = 1967;
        $LLProductID = $productID;
        $productModel = MembershipPackage::where('ll_product_id', $LLProductID)->first();

        //====== Creating Product Update Url =======//

        $product_param = $LLProductID . ',' . $LLProductID . ',' . $LLProductID . ',' . $LLProductID;
        $actions_param = 'product_name,product_sku,product_price,max_quantity';
        $value_param = urlencode($productModel->product_name) . ',' . urlencode($productModel->product_sku) . ',' . urlencode($productModel->product_price) . ',' . $productModel->product_max_quantity;
        $productUpdateUrl = 'https://' . $LLCRM_Instance . '.limelightcrm.com/admin/membership.php?username=' . $LLCRM_APIUsername . '&password=' . $LLCRM_APIPassword . '&method=product_update&product_ids=' . $product_param . '&actions=' . $actions_param . '&values=' . $value_param;

        //====== Updating The Product Details to LimeLight CRM =======//
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $productUpdateUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $curl_response = curl_exec($ch);
        parse_str($curl_response, $response_output);
        curl_close($ch);

        $resp['type'] = 'success';

        if (in_array(100, explode(",", $response_output['response_code']))) {
            $resp['msg'] = 'Success! Membership Package has been synced to LimeLight successfully.';
        } else {
            $resp['msg'] = 'No new changes has been made';
        }

        echo json_encode($resp);
    }

    public function manageMediaProduct()
    {
        if (!Auth::check()) {
            return redirect(siteurl('admin/login'));
        }
        $data = [];

        if (isset($_GET['id']) && $_GET['id'] != '') {
            $productID = $_GET['id'];
            $data['productModel'] = $productModel = MembershipPackage::find($productID);
            // echo '<pre>';
            // print_r(count($productModel->getMedia));
            // echo '<pre>';
            // exit();

            if (!$productModel) {
                return redirect(siteurl('admin/manageMembershipPackage'));
            }
        } else {
            return redirect(siteurl('admin/manageMediaMembershipPackage'));
        }
        return view('admin.membershipPackage.manageMedia', $data);
    }

    public function uploadMedia()
    {
        $resp = [];
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $productID = $_GET['id'];
            $productModel = MembershipPackage::find($productID);

            $productsMediaModel = new MembershipPackageMedia;
            $fileType = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            $fileName = $_FILES['file']['name'] ? 'PRO-' . $productModel->ll_product_id . date('s') . rand(2, 3) . '.' . $fileType : '';

            $productsMediaModel->membership_package_id = $productModel->id;
            if ($fileType == 'jpg' || $fileType == 'jpeg' || $fileType == 'png' || $fileType == 'gif') {
                $productsMediaModel->image = $fileName;
                $target_file = $_SERVER['DOCUMENT_ROOT'] . '/backend/images/membershipPackage/';
            } else {
                $productsMediaModel->document_file = $fileName;
                $target_file = $_SERVER['DOCUMENT_ROOT'] . '/backend/docs/membershipPackage/';
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
            $resp['msg'] = 'Error! No Membership Package ID found.';
        }
        echo json_encode($resp);
    }

    public function removeMembershipPackageMedia(Request $request)
    {
        $resp = [];
        $inputArr = $request->all();
        if (!Auth::check()) {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! Not authorized to this action.';
        } else {
            $productsMediaModel = MembershipPackageMedia::find($inputArr['id']);
            $imageName = $productsMediaModel->image;
            if (MembershipPackageMedia::destroy($inputArr['id'])) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/backend/images/membershipPackage/' . $imageName);
                $resp['type'] = 'success';
                $resp['msg'] = 'Successfully removed the image.';
            }
        }
        echo json_encode($resp);
        die();
    }

    public function placeOrder(Request $request)
    {
        //======== Fetching LL Credentials from ENV File =========//
        $LLCRM_Instance = env('LLCRM_Instance');
        $LLCRM_APIUsername = env('LLCRM_APIUsername');
        $LLCRM_APIPassword = env('LLCRM_APIPassword');

        $inputArr = $request->all();

        $firstName = $inputArr['firstName'];
        $lastName = $inputArr['lastName'];
        $shippingAddress1 = $inputArr['shippingAddress1'];
        $shippingAddress2 = $inputArr['shippingAddress2'];
        $shippingCity = $inputArr['shippingCity'];
        $shippingState = $inputArr['shippingState'];
        $shippingZip = $inputArr['shippingZip'];
        $shippingCountry = $inputArr['shippingCountry'];
        $billingFirstName = $inputArr['billingFirstName'];
        $billingLastName = $inputArr['billingLastName'];
        $billingAddress1 = $inputArr['billingAddress1'];
        $billingAddress2 = $inputArr['billingAddress2'];
        $billingCity = $inputArr['billingCity'];
        $billingState = $inputArr['billingState'];
        $billingZip = $inputArr['billingZip'];
        $billingCountry = $inputArr['billingCountry'];
        $phone = $inputArr['phone'];
        $email = $inputArr['email'];
        $creditCardType = $inputArr['creditCardType'];
        $creditCardNumber = $inputArr['creditCardNumber'];
        $expirationDate = $inputArr['expirationDate'];
        $CVV = $inputArr['CVV'];
        $ipAddress = $inputArr['ipAddress'];
        $AFID = $inputArr['AFID'];
        $SID = $inputArr['SID'];
        $AFFID = $inputArr['AFFID'];
        $C1 = $inputArr['C1'];
        $C2 = $inputArr['C2'];
        $C3 = $inputArr['C3'];
        $AID = $inputArr['AID'];
        $OPT = $inputArr['OPT'];
        $click_id = $inputArr['click_id'];
        $productId = $inputArr['productId'];
        $campaignId = $inputArr['campaignId'];
        $shippingId = $inputArr['shippingId'];
        $dynamic_product_price_X = $inputArr['dynamic_product_price_X'];
        $billingSameAsShipping = $inputArr['billingSameAsShipping'];
        $notes = $inputArr['notes'];
        $product_qty_X = $inputArr['product_qty_X'];
        $forceGatewayId = $inputArr['forceGatewayId'];
        $preserve_force_gateway = $inputArr['preserve_force_gateway'];
        $createdBy = $inputArr['createdBy'];
        $device_category = $inputArr['$device_category'];

        $orderPlaceUrl = 'https://' . $LLCRM_Instance . '.limelightcrm.com/admin/membership.php?' .
            'username=' . $LLCRM_APIUsername .
            '&password=' . $LLCRM_APIPassword .
            '&method=NewOrder' .
            '&firstName=' . $firstName .
            '&lastName=' . $lastName .
            '&shippingAddress1=' . $shippingAddress1 .
            '&shippingAddress2=' . $shippingAddress2 .
            '&shippingCity=' . $shippingCity .
            '&shippingState=' . $shippingState .
            '&shippingZip=' . $shippingZip .
            '&shippingCountry=' . $shippingCountry .
            '&billingFirstName=' . $billingFirstName .
            '&billingLastName=' . $billingLastName .
            '&billingAddress1=' . $billingAddress1 .
            '$&billingAddress2=' . $billingAddress2 .
            '&billingCity=' . $billingCity .
            '&billingState=' . $billingState .
            '&billingZip=' . $billingZip .
            '&billingCountry=' . $billingCountry .
            '&phone=' . $phone .
            '&email=' . $email .
            '&creditCardType=' . $creditCardType .
            '&creditCardNumber=' . $creditCardNumber .
            '&expirationDate=' . $expirationDate .
            '&CVV=' . $CVV .
            '&ipAddress=' . $ipAddress .
            '&AFID=' . $AFID .
            '&SID=' . $SID .
            '&AFFID=' . $AFFID .
            '&C1=' . $C1 .
            '&C2=' . $C2 .
            '&C3=' . $C3 .
            '&AID=' . $AID .
            '&OPT=' . $OPT .
            '&click_id=' . $click_id .
            '&productId=' . $productId .
            '&campaignId=' . $campaignId .
            '&shippingId=' . $shippingId .
            '&dynamic_product_price_X=' . $dynamic_product_price_X .
            '&billingSameAsShipping=' . $billingSameAsShipping .
            '&notes=' . $notes .
            '&product_qty_X=' . $product_qty_X .
            '&forceGatewayId=' . $forceGatewayId .
            '&preserve_force_gateway=' . $preserve_force_gateway .
            '&createdBy=' . $createdBy .
            '&device_category=' . $device_category;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $productUpdateUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $curl_response = curl_exec($ch);
        parse_str($curl_response, $response_output);
        curl_close($ch);

        if (in_array(100, explode(",", $response_output['response_code']))) {
            $resp['msg'] = 'You have successfully purchased Membership Package and Order Placed in LimeLight Successfully.';
            $resp['response_code'] = $response_output['response_code'];
        } else {
            $resp['msg'] = 'Error! Unable to place order.';
            $resp['response_code'] = $response_output['response_code'];
        }

        echo json_encode($resp);
        exit();
    }

    //======= API Methods ========//

    public function getProducts(Request $request, $status = 1, $order = 'desc', $limit = 3)
    {
        $data = [];
        $status = isset($_GET['status']) ? $_GET['status'] : $status;
        $order = isset($_GET['order']) ? $_GET['order'] : $order;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : $limit;
        $media = isset($_GET['media']) && $_GET['media'] == 1 ? 1 : 0;

        $data['type'] = 'success';
        $data['msg'] = 'success';
        if ($media == 1) {
            $products = MembershipPackage::where('status', $status)
                ->orderBy('id', $order)
                ->with('getMedia')
                ->paginate($limit);
        } else {
            $products = MembershipPackage::where('status', $status)
                ->orderBy('id', $order)
                ->paginate($limit);
        }
        $data['data'] = $products;
        return response()->json($data);
    }

    public function getProduct(Request $request)
    {
        $data = [];
        $productId = isset($_GET['id']) ? $_GET['id'] : '';
        if ($productId == '') {
            $data['type'] = 'error';
            $data['msg'] = 'Error! No Product ID provided.';
            $data['data'] = '';
        } else {
            $product = MembershipPackage::find($productId);
            $product['get_media'] = $get_media = MembershipPackage::find($productId)->getMedia;
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
