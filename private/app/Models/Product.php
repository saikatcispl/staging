<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    public function getMedia() {
        return $this->hasMany('App\Models\ProductsMedia');
    }

    public static function Rules() {

        $rules = array(
            'product_name' => 'required',
            'product_sku' => 'required',
            'product_price' => 'required|numeric',
            'product_max_quantity' => 'required|numeric',
        );
        return $rules;
    }

    public static $messages = array(
        'product_name.required' => 'Product name is required!',
        'product_sku.required' => 'Product SKU is required!',
        'product_price.required' => 'Product price is required!',
        'product_price.numeric' => 'Product price should be numeric!',
        'product_max_quantity.required' => 'Product max quantity is required!',
        'product_max_quantity.numeric' => 'Product max quantity should be numeric!'
    );

}
