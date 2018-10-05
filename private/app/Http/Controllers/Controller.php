<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getLLCreds()
    {   
        $data = [];
        //======== Fetching LL Credentials from ENV File =========//
        $data['LLCRM_Instance'] = env('LLCRM_Instance');
        $data['LLCRM_APIUsername'] = env('LLCRM_APIUsername');
        $data['LLCRM_APIPassword'] = env('LLCRM_APIPassword');
        
        return $data;
    }
}
