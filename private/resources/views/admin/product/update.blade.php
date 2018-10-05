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
            <a href="{{ URL::to('admin/manageProducts') }}">Product</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Update</span>
        </li>
    </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Update Product</h1>
<!-- END PAGE TITLE-->
<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="row">
    <div class="col-md-12 ">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="fa fa-list font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">Update : {{ $productModel->product_name }} (LL Product ID: #{{ $productModel->ll_product_id }})</span>
                </div>
                <div class="pull-right">
                    <a href="<?php echo URL::to('admin/manageMediaProduct') . '?id=' . $productModel->id; ?>" class="btn btn-success"><i class="fa fa-edit"></i> Manage Media</a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="alert alert-success" id="success_div" style="display:none;"></div>
                <form role="form" id="product_updates_form" method="post" action="<?php echo URL::to('admin/updateProduct') . '?id=' . $productModel->id; ?>">
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="form-group form-md-line-input">
                            {{Form::text('product_name', $productModel->product_name, array('class'=>"form-control",'id'=>"Product_name", 'placeholder'=> "Enter Product name"))}}
                            <label for="form_control_1">Name<span class="required">*</span></label>
                            <span class="error"></span>
                        </div>
                        <div class="form-group form-md-line-input">
                            {{Form::text('product_sku',$productModel->product_sku, array('class'=>"form-control",'id'=>"Product_product_sku", 'placeholder'=> "Enter coupon code"))}}
                            <label for="form_control_1">SKU<span class="required">*</span></label>
                            <span class="error"></span>
                        </div>
                        <div class="form-group form-md-line-input">
                            {{Form::text('vertical_name',$productModel->vertical_name, array('class'=>"form-control",'disabled'=>'disabled','id'=>"Product_vertical_name", 'placeholder'=> ""))}}
                            <label for="form_control_1">Vertical</label>
                            <span class="error"></span>
                        </div>
                        <div class="form-group form-md-line-input">
                            {{Form::text('product_category_name',$productModel->product_category_name, array('class'=>"form-control",'disabled'=>'disabled','id'=>"Product_product_category_name", 'placeholder'=> ""))}}
                            <label for="form_control_1">Category</label>
                            <span class="error"></span>
                        </div>
                        <div class="form-group form-md-line-input">
                            {{Form::text('product_price',$productModel->product_price, array('class'=>"form-control",'id'=>"Product_description", 'placeholder'=> "Enter product price"))}}
                            <label for="form_control_1">Price (MSRP)<span class="required">*</span></label>
                            <span class="error"></span>
                        </div>
                        <div class="form-group form-md-line-input">
                            {{Form::text('cost_of_goods_sold',$productModel->cost_of_goods_sold, array('class'=>"form-control",'disabled'=>'disabled','id'=>"Product_cost_of_goods_sold", 'placeholder'=> ""))}}
                            <label for="form_control_1">Cost of Goods</label>
                            <span class="error"></span>
                        </div>
                        <div class="form-group form-md-line-input">
                            {{Form::text('product_max_quantity',$productModel->product_max_quantity, array('class'=>"form-control",'id'=>"Product_product_max_quantity", 'placeholder'=> "Enter product Max Qty"))}}
                            <label for="form_control_1">Max Qty.<span class="required">*</span></label>
                            <span class="error"></span>
                        </div>
                        <div class="form-group form-md-line-input">
                            {{Form::textArea('product_description',$productModel->product_description, array('class'=>"form-control",'id'=>"Product_description", 'placeholder'=> "Enter product description"))}}
                            <label for="form_control_1">Description</label>
                            <span class="error"></span>
                        </div>
                        <div class="form-group form-md-line-input">
                            <input type="checkbox" class="make-switch" name="eligible_for_coupon" id="Product_eligible_for_coupon" <?php if ($productModel->eligible_for_coupon == 1): ?>checked<?php endif;?> data-on-color="warning" data-off-color="danger">
                            <label for="form_control_1">Eligible for Coupon</label>
                            <span class="error"></span>
                        </div>
                        <div class="form-group form-md-line-input">
                            <input type="checkbox" class="make-switch" name="priority_pricing_enable" id="Product_priority_pricing_enable" <?php if ($productModel->priority_pricing_enable == 1): ?>checked<?php endif;?> data-on-color="warning" data-off-color="danger">
                            <label for="form_control_1">Priority Pricing</label>
                            <span class="error"></span>
                        </div>
                        <div id="ppa" class="form-group form-md-line-input" <?php if ($productModel->priority_pricing_enable == 0): ?>style="display:none;"<?php endif;?>>
                            <label for="form_control_1">Priority Pricing Amount</label>
                            {{Form::text('priority_pricing_amount',$productModel->priority_pricing_amount, array('class'=>"form-control",'id'=>"Product_priority_pricing_amount", 'placeholder'=> "Enter product priority pricing asmount"))}}
                            <span class="error"></span>
                        </div>
                    </div>
                    <div class="form-actions noborder">
                        <button type="submit" id="submit_btn" class="btn blue">Save Changes</button>
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

setTimeout(function(){
    $('.bootstrap-switch').on('click', function(e){
var _this = $(this);
console.log(_this.closest('div').find('input').attr('name'));
if(_this.closest('div').find('input').attr('name') == 'priority_pricing_enable'){
if(_this.hasClass('bootstrap-switch-off')){
$('#Product_priority_pricing_enable').attr('checked',false);
}else{
$('#Product_priority_pricing_enable').attr('checked',true);
}

if ($('#Product_priority_pricing_enable').is(":checked")) {
            $('#ppa').show();
        } else {
            $('#ppa').hide();
        }

}

if(_this.closest('div').find('input').attr('name') == 'eligible_for_coupon'){
if(_this.hasClass('bootstrap-switch-off')){
$('#Product_eligible_for_coupon').attr('checked',false);
}else{
$('#Product_eligible_for_coupon').attr('checked',true);
}



}

});
 }, 3000);



    $('#product_updates_form').validate({// initialize the plugin
        rules: {
            product_name: {
                required: true
            },
            product_sku: {
                required: true
            },
            product_price: {
                required: true
            },
            product_max_quantity: {
                required: true
            },
        },
        messages: {
            product_name: "Product Name can not be blank!!",
            product_sku: "Product SKU can not be blank!!",
            product_price: "Product Price can not be blank!!",
            product_max_quantity: "Product Max Qty. can not be blank!!",
        },
        errorElement: 'div',
        errorLabelContainer: '.errorTxt',
//            submitHandler: function (form) {
//                $.ajax({
//                    url: $('#product_updates_form').attr('action'),
//                    type: "POST",
//                    data: $('#product_updates_form').serialize(),
//                    dataType: "json",
//                    cache: false,
//                    processData: false,
//                    success: function (resp) {
//                        if (resp.type == 'success') {
//                            window.location.href = "{{ URL::to('admin/manageProducts') }}";
//                        } else {
//                            $('#error_div').html(resp.msg);
//                            $('#error_div').show();
//                        }
//                    }
//                });
//                return false;
//            }
    });

});
</script>
@endsection
