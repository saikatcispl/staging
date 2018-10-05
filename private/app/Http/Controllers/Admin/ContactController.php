<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
//use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create(Request $request)
    {
        $resp = [];
        $inputArr = $request->all();

        if ($inputArr['phone'] != '') {
            $contactModel = new Contact;
                        
            $inputArr['status'] = 1;
            $inputArr['created_at'] = date('Y-m-d H:i:s');
            
            if (Contact::insert($inputArr)) {
                $resp['type'] = 'success';
                $resp['msg'] = 'Contact request has been posted successfully.';
            } else {
                $resp['type'] = 'error';
                $resp['msg'] = 'Some thing went wrong!!.';
            }
        } else {
            $resp['type'] = 'error';
            $resp['msg'] = 'Please fill up the required fields.';
        }

        echo json_encode($resp);
        die();
        
    }
}
