<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Yajra\Datatables\Datatables;

class UserController extends BaseController
{

    use AuthorizesRequests,
    DispatchesJobs,
        ValidatesRequests;

    public function index()
    {
        return view('admin.user.index');
    }

    /**
     * Process dataTable ajax response.
     *
     * @param \Yajra\Datatables\Datatables $datatables
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Datatables $datatables, Request $request)
    {
        $user = User::query('role_id', '!', 1);

        return $datatables->eloquent($user)
            ->filterColumn('role_id', function ($query, $keyword) {
                $role_id = 1;
                if ($keyword != '') {
                    if (($keyword == 'superadmin') || ($keyword == 'SuperAdmin') || ($keyword == 'SUPERADMIN')) {
                        $role_id = 1;
                    }
                    if (($keyword == 'admin') || ($keyword == 'Admin') || ($keyword == 'ADMIN')) {
                        $role_id = 2;
                    }
                    if (($keyword == 'distributor') || ($keyword == 'Distributor') || ($keyword == 'DISTRIBUTOR')) {
                        $role_id = 3;
                    }
                    if (($keyword == 'customer') || ($keyword == 'Customer') || ($keyword == 'CUSTOMER')) {
                        $role_id = 4;
                    }
                    $query->whereRaw("role_id =" . $role_id);
                } else {
                    $query->where("role_id", '!', $role_id);
                }
            })
            ->editColumn('role_id', function ($user) {
                $usertype = '';
                if ($user->role_id == 1) {
                    $usertype = 'SuperAdmin';
                }
                if ($user->role_id == 2) {
                    $usertype = 'Admin';
                }
                if ($user->role_id == 3) {
                    $usertype = 'Distributor';
                }
                if ($user->role_id == 4) {
                    $usertype = 'Customer';
                }
                return $usertype;
            })
            ->editColumn('action', function ($user) {
                return '<a title="View"><i class="fa fa-eye"></i></a>'
                    . '&nbsp;&nbsp;&nbsp;'
                    . '<a title="Edit"><i class="fa fa-edit"></i></a>'
                    . '&nbsp;&nbsp;&nbsp;';
            })
            ->make(true);
    }

//    public function data(Datatables $datatables) {
    //        return $datatables->eloquent(User::query())
    //                        ->editColumn('username', function ($user) {
    //                            return '<a>' . $user->username . '</a>';
    //                        })
    //                        ->editColumn('action', function ($user) {
    //                            return '<a>' . 'Edit' . '</a>';
    //                        })
    //                        ->rawColumns(['username', 'action'])
    //                        ->make(true);
    //    }

    public function getCountryList(Request $request)
    {
        $requiredCountries = $request->all();

        if (!empty($requiredCountries)) {
            $requiredCountries = explode(',', $requiredCountries['id']);
        }
        $data = [];
        if (count($requiredCountries) > 0) {
            $countryList = Country::whereIn('country_iso_code_2', $requiredCountries)->get();
        } else {
            $countryList = Country::all();
        }

        if (count($countryList) > 0) {
            $data['type'] = 'success';
            $data['msg'] = 'success';
            $data['data'] = $countryList;
        } else {
            $data['type'] = 'error';
            $data['msg'] = 'Error! No data exists.';
            $data['data'] = '';
        }

        return response()->json($data);
    }

    public function getStateList(Request $request)
    {
        $data = [];
        $countryCode = $request->all();
        if (isset($countryCode['id']) && $countryCode['id'] != '') {
            $country = Country::where('country_iso_code_2', $countryCode['id'])->first();
            $stateList = State::where('country_id', $country->country_id)->get();

            if (count($stateList) > 0) {
                $data['type'] = 'success';
                $data['msg'] = 'success';
                $data['data'] = $stateList;
            } else {
                $data['type'] = 'error';
                $data['msg'] = 'Error! No data exists.';
                $data['data'] = '';
            }
        } else {
            $data['type'] = 'error';
            $data['msg'] = 'Error! No Country selected.';
            $data['data'] = '';
        }

        return response()->json($data);
    }

    public function postSigninForm(Request $request)
    {
        $data = [];
        $inputArr = $request->all();

        if (isset($inputArr['email'])) {

            $model = new User;

            $isUserEmailExists = $model->checkUserEmailExists($inputArr['email']);

            if ($isUserEmailExists == 1) {
                $resp['type'] = 'error';
                $resp['msg'] = 'Error! Email address already exists.';
            } else {
                $inputArr['role_id'] = 4;
                $inputArr['email'] = isset($inputArr['email']) ? $inputArr['email'] : '';
                $inputArr['password'] = isset($inputArr['password']) ? Hash::make(trim($inputArr['password'])) : '';
                $inputArr['firstName'] = isset($inputArr['firstName']) ? $inputArr['firstName'] : '';
                $inputArr['lastName'] = isset($inputArr['lastName']) ? $inputArr['lastName'] : '';
                $inputArr['username'] = $inputArr['firstName'] . '_' . $inputArr['lastName'];
                $inputArr['taxId'] = isset($inputArr['taxId']) ? $inputArr['taxId'] : '';
                $inputArr['phone'] = isset($inputArr['phone']) ? $inputArr['phone'] : '';
                $inputArr['address'] = isset($inputArr['address']) ? $inputArr['address'] : '';
                $inputArr['address2'] = isset($inputArr['address2']) ? $inputArr['address2'] : '';
                $inputArr['city'] = isset($inputArr['city']) ? $inputArr['city'] : '';
                $inputArr['country'] = isset($inputArr['country']) ? $inputArr['country'] : '';
                $inputArr['state'] = isset($inputArr['state']) ? $inputArr['state'] : '';
                $inputArr['zip'] = isset($inputArr['zip']) ? $inputArr['zip'] : '';
                $inputArr['comments'] = isset($inputArr['comments']) ? $inputArr['comments'] : '';
                $inputArr['created_at'] = date('Y-m-d H:i:s');
                $inputArr['email_verification_token'] = md5(time());
                $inputArr['phone_verification_token'] = rand(9999, 999999);
                $inputArr['status'] = 0;
                $inputArr['campaignId'] = isset($inputArr['campaignId']) ? $inputArr['campaignId'] : '1030';
                $inputArr['ipAddress'] = $_SERVER['REMOTE_ADDR'];
                $inputArr['AFID'] = isset($inputArr['AFID']) ? $inputArr['AFID'] : '';
                $inputArr['SID'] = isset($inputArr['SID']) ? $inputArr['SID'] : '';
                $inputArr['AFFID'] = isset($inputArr['AFFID']) ? $inputArr['AFFID'] : '';
                $inputArr['C1'] = isset($inputArr['C1']) ? $inputArr['C1'] : '';
                $inputArr['C2'] = isset($inputArr['C2']) ? $inputArr['C2'] : '';
                $inputArr['C3'] = isset($inputArr['C3']) ? $inputArr['C3'] : '';
                $inputArr['AID'] = isset($inputArr['AID']) ? $inputArr['AID'] : '';
                $inputArr['OPT'] = isset($inputArr['OPT']) ? $inputArr['OPT'] : '';
                $inputArr['click_id'] = isset($inputArr['click_id']) ? $inputArr['click_id'] : '';
                // $inputArr['notes'] = 'Prospect Created by ' . env('LLCRM_APIUsername');

                $llProspectCreate = self::createProspect($inputArr);
                if ($llProspectCreate == '100') {
                    unset( $inputArr['campaignId'],$inputArr['ipAddress'],$inputArr['AFID'],$inputArr['SID'],$inputArr['AFFID'],$inputArr['C1'],$inputArr['C2'],$inputArr['C3'],$inputArr['AID'],$inputArr['OPT'],$inputArr['click_id']);
                    if (User::insert($inputArr)) {
                        $resp['type'] = 'success';
                        $resp['msg'] = 'Congratulations! You have successfully registered.';
                    } else {
                        $resp['type'] = 'error';
                        $resp['msg'] = 'Error! Something went wrong while saving to Local DB. Prospect Created in LL. Please try after sometime.';
                    }
                } else {
                    $resp['type'] = 'error';
                    $resp['msg'] = 'Error! Something went wrong. Please try after sometime.';
                }
            }
        } else {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! Something went wrong. No data posted.';
        }

        echo json_encode($resp);
        die();
    }

    public function createProspect($inputArr)
    {
        //======== Fetching LL Credentials from ENV File =========//
        $LLCRM_Instance = env('LLCRM_Instance');
        $LLCRM_APIUsername = env('LLCRM_APIUsername');
        $LLCRM_APIPassword = env('LLCRM_APIPassword');

        $createProspectUrl = 'https://' . $LLCRM_Instance . '.limelightcrm.com/admin/transact.php?' .
            'username=' . $LLCRM_APIUsername .
            '&password=' . $LLCRM_APIPassword .
            '&method=NewProspect' .
            '&campaignId=' . $inputArr['campaignId'] .
            '&email=' . $inputArr['email'] .
            '&ipAddress=' . $inputArr['ipAddress'] .
            '&firstName=' . $inputArr['firstName'] .
            '&lastName=' . $inputArr['lastName'] .
            '&address1=' . $inputArr['address'] .
            '&address2=' . $inputArr['address2'] .
            '&city=' . $inputArr['city'] .
            '&state=' . $inputArr['state'] .
            '&zip=' . $inputArr['zip'] .
            '&country=' . $inputArr['country'] .
            '&phone=' . $inputArr['phone'] .
            '&AFID=' . $inputArr['AFID'] .
            '&SID=' . $inputArr['SID'] .
            '&AFFID=' . $inputArr['AFFID'] .
            '&C1=' . $inputArr['C1'] .
            '&C2=' . $inputArr['C2'] .
            '&C3=' . $inputArr['C3'] .
            '&AID=' . $inputArr['AID'] .
            '&OPT=' . $inputArr['OPT'] .
            '&click_id=' . $inputArr['click_id'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $createProspectUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $curl_response = curl_exec($ch);
        parse_str($curl_response, $response_output);
        curl_close($ch);
        return $response_output['responseCode'];
    }

}
