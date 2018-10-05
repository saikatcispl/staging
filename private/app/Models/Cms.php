<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cms extends Model
{
    protected $table = 'cms';

    public static function Rules() {
$codeRule = '';
        if (!isset($_REQUEST['id'])) {
            $codeRule = 'required|unique:cms';
        }

        $rules = array(
            'name' => 'required|max:40',
            'slug' => $codeRule,
            'content' => 'required'
        );
        return $rules;
    }

    public static $messages = array(
        'name.required' => 'CMS name is required!',
        'slug.required' => 'CMS slug is required!',
        'slug.unique' => 'CMS slug must be unique!',
        'content.required' => 'CMS content is required!'
    );
}
