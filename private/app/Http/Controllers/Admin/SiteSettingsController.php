<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GenConfig;
use Auth;
use Illuminate\Http\Request;
use Redirect;
use Session;
use View;

class SiteSettingsController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect(siteurl('admin/login'));
        }

        $data = [];
        $data['model'] = $model = GenConfig::find(1);

        if (isset($_POST['_token']) && $_POST['_token'] != '') {
            $inputArr = $request->all();
            unset($inputArr['_token']);

            $model->site_title = $inputArr['site_title'];
            $model->corp_name = $inputArr['corp_name'];
            $model->corp_address = $inputArr['corp_address'];
            $model->return_address = $inputArr['return_address'];
            $model->operating_hours = $inputArr['operating_hours'];
            $model->customer_support_email = $inputArr['customer_support_email'];
            $model->customer_support_no = $inputArr['customer_support_no'];
            $model->fb_url = $inputArr['fb_url'];
            $model->twitter_url = $inputArr['twitter_url'];
            $model->updated_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                Session::flash('success', 'Site Setting has been updated successfully !!');
            } else {
                Session::flash('error', 'Something went wrong!!');
            }
            return Redirect::action('Admin\SiteSettingsController@index');
        }

        return view('admin.siteSettings.index', $data);
    }

    public function getSiteSettings(Request $request)
    {
        $data = [];
        $siteSettingData = GenConfig::find(1);
        if ($siteSettingData) {
            $data['type'] = 'success';
            $data['msg'] = 'success';
            $data['data'] = $siteSettingData;
        } else {
            $data['type'] = 'error';
            $data['msg'] = 'Error! No data exists for Site Settings.';
            $data['data'] = '';
        }
        return response()->json($data);
    }

}
