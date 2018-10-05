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
            <a href="{{ URL::to('admin/manageProducts') }}">Products</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Add</span>
        </li>
    </ul>    
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Add Product to Sync from LimeLight CRM</h1>
<!-- END PAGE TITLE-->
<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="row">
    <div class="col-md-12 ">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="fa fa-list font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">Add Product</span>
                </div>                                       
            </div>
            <div class="portlet-body form">
                <div class="alert alert-danger" id="error_div" style="display:none;"></div>
                <form role="form" id="ll_product_add_form" method="post" action="{{ URL::to('admin/addProductLL') }}">
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="form-group form-md-line-input">
                            {{Form::text('ll_product_id', $productModel->ll_product_id, array('class'=>"form-control",'id'=>"Product_ll_product_id", 'placeholder'=> "Enter Limelight CRM Product ID here."))}}
                            <label for="form_control_1">LimeLight CRM Product ID<span class="required">*</span></label>
                            <span class="error"></span>
                        </div>
                    </div>
                    <div class="form-actions noborder">
                        <button type="submit" id="submit_btn" class="btn blue">Create</button>
                        <a href="{{ URL::to('admin/manageProducts') }}" class="btn default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>


<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
$(document).ready(function () {
    $('#ll_product_add_form').validate({// initialize the plugin
        rules: {
            ll_product_id: {
                required: true
            }
        },
        messages: {
            ll_product_id: "LimeLight Product ID can not be blank!"
        },
        errorElement: 'div',
        errorLabelContainer: '.errorTxt',
        submitHandler: function (form) {
            $.ajax({
                url: $('#ll_product_add_form').attr('action'),
                type: "POST",
                data: $('#ll_product_add_form').serialize(),
                dataType: "json",
                cache: false,
                processData: false,
                success: function (resp) {
                    if (resp.type == 'success') {
                        window.location.href = "{{ URL::to('admin/manageProducts') }}";
                    } else {
                        $('#error_div').html(resp.msg);
                        $('#error_div').show();
                    }
                }
            });
            return false;
        },
    });
});

</script>


@endsection
