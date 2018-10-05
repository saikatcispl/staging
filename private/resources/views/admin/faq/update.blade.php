@extends('admin.layouts.app')
@section('content')

<style>
    .error{color: red;}
</style>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="{{ URL::to('admin/dashboard') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ URL::to('admin/manageFaqs') }}">Faq</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Update</span>
        </li>
    </ul>    
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Update Faq</h1>
<!-- END PAGE TITLE-->
<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="row">
    <div class="col-md-12 ">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="fa fa-list font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">Update Faq</span>
                </div>                                       
            </div>
            <div class="portlet-body form">
                <div class="alert alert-success" id="success_div" style="display:none;"></div>
                @include('admin.faq._form', array('faqModel' => $faqModel, 'isNew' => $isNew))
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>
@endsection