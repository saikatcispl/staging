<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model {

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    Protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name, code, description, discount_type, discount, status'];

    public static function Rules() {

        if (isset($_REQUEST['cid']) && $_REQUEST['cid'] != '') {
            $coupons = Coupon::find($_REQUEST['cid']);
            $codeRule = 'required|unique:coupons,code,' . $coupons->id;
        } else {
            $codeRule = 'required|unique:coupons';
        }

        $rules = array(
            'name' => 'required|max:40',
            'code' => $codeRule,
            'description' => 'required',
            'discount_type' => 'required',
            'discount' => 'required',
            'start_datetime' => 'required',
            'end_datetime' => 'required',
        );
        return $rules;
    }

    public static $messages = array(
        'name.required' => 'Coupon name is required!',
        'code.required' => 'Coupon code is required!',
        'code.unique' => 'Coupon code must be unique!',
        'description.required' => 'Description is required!',
        'discount_type.required' => 'Coupon discount_type is required!',
        'discount.required' => 'Discount is required!',
        'start_datetime.required' => 'Coupon start date is required!',
        'end_datetime.required' => 'Coupon end date is required!',
    );
}
