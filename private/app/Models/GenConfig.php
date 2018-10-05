<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenConfig extends Model
{
    protected $table = 'gen_config';

    protected $fillable  = ['id','site_title','corp_name','corp_address','return_address','operating_hours','customer_support_email','customer_support_no','updated_at'];
}



