<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    Protected $primaryKey = "id";

    protected $fillable = ['department_id, name, phone, email, subject, message, status, created_at, updated_at'];

    protected $table = 'contact';
}
