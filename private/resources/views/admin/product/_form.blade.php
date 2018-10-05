@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if(Session::has('success'))
<p class="alert alert-success">{{ Session::get('success') }}</p>
@endif
@if(Session::has('error'))
<p class="alert alert-danger">{{ Session::get('error') }}</p>
@endif
<form role="form" id="coupon_form" method="post" action="<?php echo $isNew == 'yes' ? URL::to('admin/addCoupon') : URL::to('admin/updateCoupon').'?cid='.$couponModel->id; ?>">
    {{ csrf_field() }}
    <?php
    if ($isNew == 'no'):
        ?>
        <input type="hidden" name="id" value="<?php echo $couponModel->id; ?>">
    <?php endif; ?>
    <div class="form-body">
        <div class="form-group form-md-line-input">
            {{Form::text('name', $couponModel->name, array('class'=>"form-control",'id'=>"Coupon_name", 'placeholder'=> "Enter coupon name"))}}
            <label for="form_control_1">Name<span class="required">*</span></label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            {{Form::text('code',$couponModel->code, array('class'=>"form-control",'id'=>"Coupon_code", 'placeholder'=> "Enter coupon code"))}}
            <label for="form_control_1">Code<span class="required">*</span></label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            {{Form::text('description',$couponModel->description, array('class'=>"form-control",'id'=>"Coupon_description", 'placeholder'=> "Enter coupon description"))}}
            <label for="form_control_1">Description<span class="required">*</span></label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            {{ Form::select('discount_type', ['' => 'Select Discount Type', 1 => 'Percentage',2 => 'Amount'], $couponModel->discount_type, array('class' => 'form-control', 'id'=> "Coupon_discount_type")) }}
            <label for="form_control_1">Discount Type<span class="required">*</span></label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            {{Form::text('discount',$couponModel->discount, array('class'=>"form-control",'id'=>"Coupon_discount", 'placeholder'=> "Enter coupon discount"))}}
            <label for="form_control_1">Discount<span class="required">*</span></label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            <div class=" col-md-6">
                <div class="input-group date form_datetime form_datetime bs-datetime">
                    {{Form::text('start_datetime',$couponModel->start_datetime, array('class'=>"form-control",'id'=>"Coupon_start_datetime"))}}
                    <span class="input-group-addon">
                        <button class="btn default date-set" type="button">
                            <i class="fa fa-calendar"></i>
                        </button>
                    </span>
                </div>
                <label for="form_control_1">Start Time<span class="required">*</span></label>
                <span class="error"></span>
            </div>
            <div class=" col-md-6">
                <div class="input-group date form_datetime form_datetime bs-datetime">
                    {{Form::text('end_datetime',$couponModel->end_datetime, array('class'=>"form-control",'id'=>"Coupon_end_datetime"))}}
                    <span class="input-group-addon">
                        <button class="btn default date-set" type="button">
                            <i class="fa fa-calendar"></i>
                        </button>
                    </span>
                </div>
                <label for="form_control_1">End Time<span class="required">*</span></label>
                <span class="error"></span>
            </div>
        </div>
    </div>
    <div class="form-actions noborder">
        <button type="submit" id="submit_btn" class="btn blue"><?php echo $isNew == 'yes' ? 'Create' : 'Save'; ?></button>
        <a href="{{ URL::to('admin/manageCoupons') }}" class="btn default">Cancel</a>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
$(document).ready(function () {
    $('#coupon_form').validate({// initialize the plugin
        rules: {
            name: {
                required: true
            },
            code: {
                required: true
            },
            description: {
                required: true
            },
            discount_type: {
                required: true
            },
            discount: {
                required: true
            }
        },
        messages: {
            name: "Coupon name can not be blank!",
            code: "Coupon code can not be blank!",
            description: "Description can not be blank!",
            discount_type: "Please select a discount type!",
            discount: "Discount can not be blank!"
        },
        errorElement: 'div',
        errorLabelContainer: '.errorTxt'
    });
});

</script>

