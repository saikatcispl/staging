<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model {

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    Protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['question, answer, status'];

    public static function Rules() {

//        if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
//            $faqs = Faq::find($_REQUEST['id']);
//            $questionRule = 'required|unique:faqs,question,' . $faqs->id;
//        } else {
//            $questionRule = 'required|unique:coupons';
//        }

        $rules = array(
//            'question' => $questionRule,
            'answer' => 'required'
        );
        return $rules;
    }

    public static $messages = array(
        'question.required' => 'Question is required!',
        'answer.required' => 'Answer is required!'
    );

}
