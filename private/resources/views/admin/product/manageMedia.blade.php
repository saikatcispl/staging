@extends('admin.layouts.app')
@section('content')

<style>
    .error{color: red;}
</style>
<link href="{{ asset('backend/assets/pages/css/basic.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('backend/assets/pages/css/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="{{ URL::to('admin/dashboard') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ URL::to('admin/manageProducts') }}">Product</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Manage Media</span>
        </li>
    </ul>    
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Manage Media</h1>
<!-- END PAGE TITLE-->
<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="row">
    <div class="col-md-12 ">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="fa fa-list font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">Manage Media for : {{ $productModel->product_name }} (LL Product ID: #{{ $productModel->ll_product_id }})</span>                    
                </div> 
            </div>
            <div class="portlet-body form">
                <?php if(count($productModel->getMedia) > 0):?>
                <div class="tile image">
                    <div class="tile-body">
                        <img src="{{ asset("backend/images/products/$productModel->getMedia->image") }}" alt=""> </div>
<!--                    <div class="tile-object">
                        <div class="name"> Media </div>
                    </div>-->
                </div>
                <?php endif;?>
                <div class="alert alert-success" id="success_div" style="display:none;"></div>
                <form role="form" id="manageMedia_form" class="dropzone" method="post" action="<?php echo URL::to('admin/uploadMedia') . '?id=' . $productModel->id; ?>" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="fallback">
                        <input name="file" type="file" multiple />
                    </div>
                </form>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>

<script src="{{ asset('backend/assets/pages/scripts/dropzone.js') }}"></script>
<script>
$(document).ready(function () {
    Dropzone.options.myAwesomeDropzone = {
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 2,
        url: $('form').attr('action'),
        dictDefaultMessage: 'Add Product Images and Media here. Drop files here to upload.',
        dictRemoveFile: false,
        acceptedFiles: 'image/*,application/pdf',
        success: function (event, resp) {
            console.log(resp);
        }
    };

});
</script>
@endsection
