<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPackage extends Model
{   
    protected $table = 'membership_packages';

    public function getMedia() {
        return $this->hasMany('App\Models\MembershipPackageMedia');
    }

    public static function Rules() {

        $rules = array(
            'product_name' => 'required',
            'product_sku' => 'required',
            'product_price' => 'required|numeric'
        );
        return $rules;
    }

    public static $messages = array(
        'product_name.required' => 'Product name is required!',
        'product_sku.required' => 'Product SKU is required!',
        'product_price.required' => 'Product price is required!',
        'product_price.numeric' => 'Product price should be numeric!'
    );
}
