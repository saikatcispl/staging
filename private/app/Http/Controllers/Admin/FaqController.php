<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Faq;
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

class FaqController extends Controller {

    public function data(Datatables $datatables) {
        return $datatables->eloquent(Faq::query()->where('status', 1))
                        ->editColumn('action', function ($faq) {
                            return '<a href="' . siteurl('admin/updateFaq') . '?id=' . $faq->id . '" title="Edit"><i class="fa fa-edit"></i></a>'
                                    . '&nbsp;&nbsp;&nbsp;'
                                    . '<a href="javascript:void(0)" data-id="' . $faq->id . '" class="item-remove" title="Remove" style="color:red;"><i class="fa fa-remove"></i></a>';
                        })
                        ->make(true);
    }

    public function index(Request $request) {
        if (!Auth::check()) {
            return redirect(siteurl('admin/login'));
        }
        return view('admin.faq.index');
    }

    public function view() {
        
    }

    public function add(Request $request) {
        $data = [];
        $data['faqModel'] = $faqModel = new Faq;
        $data['isNew'] = 'yes';

        if (isset($_POST['_token']) && $_POST['_token'] != '') {
            $inputArr = $request->all();
            unset($inputArr['_token']);

            $validator = Validator::make($inputArr, Faq::Rules(), Faq::$messages);
            if ($validator->fails()) {
                return redirect('admin/addFaq')
                                ->withErrors($validator)
                                ->withInput();
//                return \Redirect::back()->withErrors($validator)->withInput();
            } else {
                $inputArr['status'] = 1;
                $inputArr['created_at'] = date('Y-m-d H:i:s');

                if (Faq::insert($inputArr)) {
                    Session::flash('success', 'Faq has been added successfully !!');
                } else {
                    Session::flash('error', 'Some thing went wrong!!');
                }
            }
            return Redirect::action('Admin\FaqController@index');
        }

        return view('admin.faq.add', $data);
    }

    public function update(Request $request) {
        if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
            $data = [];
            $data['faqModel'] = $faqModel = Faq::find($_REQUEST['id']);
            $data['isNew'] = 'no';

            if (!$faqModel) {
                return redirect(siteurl('admin/manageFaqs'));
            } else {
                if (isset($_POST['_token']) && $_POST['_token'] != '') {
                    $data = [];
                    $data['faqModel'] = $faqModel = Faq::find($_POST['id']);
                    $data['isNew'] = 'no';
                    $inputArr = $request->all();
                    unset($inputArr['_token']);
                    $validator = Validator::make($inputArr, Faq::Rules());

                    if ($validator->fails()) {
                        return redirect('admin/updateFaq')
                                        ->withErrors($validator)
                                        ->withInput();
                    } else {
                        $faqModel->question = $inputArr['question'];
                        $faqModel->answer = $inputArr['answer'];
                        $faqModel->status = 1;
                        $faqModel->updated_at = date('Y-m-d H:i:s');

                        if ($faqModel->save()) {
                            Session::flash('success', 'Faq has been updated successfully !!');
                        } else {
                            Session::flash('error', 'Some thing went wrong!!');
                        }
                        return Redirect::action('Admin\FaqController@index');
                    }
                }
                return view('admin.faq.update', $data);
            }
        } else {
            return redirect(siteurl('admin/manageFaqs'));
        }
    }

    public function remove() {
        $resp = [];

        if (isset($_GET['id']) && $_GET['id'] != '') {
            $faqModel = Faq::find($_GET['id']);
            $faqModel->status = 3;

            if ($faqModel->save()) {
                $resp['type'] = 'success';
                $resp['msg'] = 'Success. Faq deleted successfully.';
            } else {
                $resp['type'] = 'error';
                $resp['msg'] = 'Error! Unable to delete faq.';
            }
        } else {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! No faq Id found.';
        }
        echo json_encode($resp);
        die();
    }

    //======= API Methods ========//

    public function getFaqs(Request $request, $status = 1, $order = 'desc', $limit = 10) {
        $data = [];
        $status = isset($_GET['status']) ? $_GET['status'] : $status;
        $order = isset($_GET['order']) ? $_GET['order'] : $order;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : $limit;

        $faqs = Faq::where('status', $status)
                ->orderBy('id', $order)
                ->take($limit)
                ->get();

        $data['type'] = 'success';
        $data['msg'] = 'success';
        $data['data'] = $faqs;

        return response()->json($data);
    }

    public function getFaq(Request $request) {
        $data = [];
        $faqId = isset($_GET['id']) ? $_GET['id'] : '';
        if ($faqId == '') {
            $data['type'] = 'error';
            $data['msg'] = 'Error! No FAQ ID provided.';
            $data['data'] = '';
        } else {
            $faq = Faq::find($faqId);
            if ($faq) {
                $data['type'] = 'success';
                $data['msg'] = 'success';
                $data['data'] = $faq;
            } else {
                $data['type'] = 'error';
                $data['msg'] = 'Error! No data exists for the fiven FAQ ID.';
                $data['data'] = '';
            }
        }
        return response()->json($data);
    }

}
