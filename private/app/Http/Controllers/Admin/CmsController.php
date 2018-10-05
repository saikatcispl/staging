<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cms;
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

class CmsController extends Controller {

    public function data(Datatables $datatables) {
        return $datatables->eloquent(Cms::query()->where('status', 1))
                        ->editColumn('action', function ($cms) {
                            return '<a href="' . siteurl('admin/updateCms') . '?id=' . $cms->id . '" title="Edit"><i class="fa fa-edit"></i></a>'
                                    . '&nbsp;&nbsp;&nbsp;'
                                    . '<a href="javascript:void(0)" data-id="' . $cms->id . '" class="item-remove" title="Remove" style="color:red;"><i class="fa fa-remove"></i></a>';
                        })
                        ->make(true);
    }

    public function index(Request $request) {
        if (!Auth::check()) {
            return redirect(siteurl('admin/login'));
        }
        return view('admin.cms.index');
    }

    public function view() {
        
    }

    public function add(Request $request) {
        $data = [];
        $data['cmsModel'] = $cmsModel = new Cms;
        $data['isNew'] = 'yes';

        if (isset($_POST['_token']) && $_POST['_token'] != '') {
            $inputArr = $request->all();
            unset($inputArr['_token']);

            $validator = Validator::make($inputArr, Cms::Rules(), Cms::$messages);
            if ($validator->fails()) {
                return redirect('admin/addCms')
                                ->withErrors($validator)
                                ->withInput();
            } else {
                $inputArr['status'] = 1;
                $inputArr['created_at'] = date('Y-m-d H:i:s');

                if (cms::insert($inputArr)) {
                    Session::flash('success', 'Cms has been added successfully !!');
                } else {
                    Session::flash('error', 'Some thing went wrong!!');
                }
            }
            return Redirect::action('Admin\CmsController@index');
        }

        return view('admin.cms.add', $data);
    }

    public function update(Request $request) {
        if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
            $data = [];
            $data['cmsModel'] = $cmsModel = Cms::find($_REQUEST['id']);
            $data['isNew'] = 'no';

            if (!$cmsModel) {
                return redirect(siteurl('admin/manageCms'));
            } else {
                if (isset($_POST['_token']) && $_POST['_token'] != '') {
                    $data = [];
                    $data['cmsModel'] = $cmsModel = Cms::find($_POST['id']);
                    $a = $cmsModel->slug;
                    $data['isNew'] = 'no';
                    $inputArr = $request->all();
                    unset($inputArr['_token']);
                    $validator = Validator::make($inputArr, Cms::Rules());
                    $cmsModel->slug = $a;
                    if ($validator->fails()) {
                        return redirect()->action(
                            'Admin\CmsController@update', ['id' => $cmsModel->id]
                        )
                        ->withErrors($validator)
                        ->withInput();
                        // return redirect('admin/updatecms',['id'=>$cmsModel->id])
                        //                 ->withErrors($validator)
                        //                 ->withInput();
                    } else {
                        $cmsModel->name = $inputArr['name'];
                        
                        $cmsModel->content = $inputArr['content'];
                        $cmsModel->status = 1;
                        $cmsModel->updated_at = date('Y-m-d H:i:s');

                        if ($cmsModel->save()) {
                            Session::flash('success', 'Cms has been updated successfully !!');
                        } else {
                            Session::flash('error', 'Something went wrong!!');
                        }
                        return Redirect::action('Admin\CmsController@index');
                    }
                }
                return view('admin.cms.update', $data);
            }
        } else {
            return redirect(siteurl('admin/manageCms'));
        }
    }

    public function remove() {
        $resp = [];

        if (isset($_GET['id']) && $_GET['id'] != '') {
            $cmsModel = Cms::find($_GET['id']);
            $cmsModel->status = 3;

            if ($cmsModel->save()) {
                $resp['type'] = 'success';
                $resp['msg'] = 'Success. Cms deleted successfully.';
            } else {
                $resp['type'] = 'error';
                $resp['msg'] = 'Error! Unable to delete cms.';
            }
        } else {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! No cms Id found.';
        }
        echo json_encode($resp);
        die();
    }

   

    //======= API Methods ========//

    public function getCms(Request $request) {
        $data = [];
        $cmsSlug = isset($_GET['slug']) ? $_GET['slug'] : '';
        if ($cmsSlug == '') {
            $data['type'] = 'error';
            $data['msg'] = 'Error! No Cms ID provided.';
            $data['data'] = '';
        } else {
            $cms = Cms::where('slug', $cmsSlug)->first();;
            if ($cms) {
                $data['type'] = 'success';
                $data['msg'] = 'success';
                $data['data'] = $cms;
            } else {
                $data['type'] = 'error';
                $data['msg'] = 'Error! No data exists for the given Cms ID.';
                $data['data'] = '';
            }
        }
        return response()->json($data);
    }

}
