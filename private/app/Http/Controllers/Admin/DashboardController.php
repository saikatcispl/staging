<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Route;
use Auth;

class DashboardController extends BaseController {

    public function index(Request $request) {
        if (!Auth::check()) {
            return redirect(siteurl('admin/login'));
        }
        
        $value = $request->session()->pull('user_role');
        return view('admin.dashboard.index');
    }

}
