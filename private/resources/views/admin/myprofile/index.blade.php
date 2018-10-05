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
            <span>My Profile</span>
        </li>
    </ul>    
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> My Profile</h1>
<!-- END PAGE TITLE-->
<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="row">
    <div class="col-md-12 ">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-user font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">Update My Profile</span>
                </div>                                       
            </div>
            <div class="portlet-body form">
                <div class="alert alert-success" id="success_div" style="display:none;"></div>
                <form role="form" id="my_profile_form" method="post" action="{{ URL::to('admin/updateMyProfile') }}">
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="form-group form-md-line-input">
                            <input type="text" class="form-control" id="User_username" data-type="username" name="username" placeholder="Enter your Username" value="{{ $userModel->username }}">
                            <label for="form_control_1">Username<span class="required">*</span></label>
                            <span class="error"></span>
                        </div>
                        <div class="form-group form-md-line-input">
                            <input type="email" class="form-control" id="User_email" data-type="email" name="email" placeholder="Enter your Email" value="{{ $userModel->email }}">
                            <label for="form_control_1">Email<span class="required">*</span></label>
                            <span class="error"></span>
                        </div>
                        <div class="form-group form-md-line-input">
                            <input type="text" class="form-control" id="User_password" data-type="password" name="password" placeholder="Enter your new password">
                            <label for="form_control_1">Password</label>
                            <span>Note: Leave blank, if don't want to update.</span>
                        </div>
                    </div>
                    <div class="form-actions noborder">
                        <button type="button" id="submit_btn" class="btn blue">Submit</button>
                        <a href="{{ URL::to('admin/dashboard') }}" class="btn default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>

<script>
    $(document).ready(function () {
        $('input').on('blur', function (e) {
            var _this = $(this);
            var input_type = _this.data('type');
            if (input_type == 'username' || input_type == 'email') {
                var request = $.ajax({
                    url: "{{ URL::to('admin/myProfileValidateInputs') }}" + '?input_type=' + input_type,
                    method: "POST",
                    data: $('form').serialize(),
                    dataType: "json"
                });
                request.done(function (resp) {
                    if (resp.type == 'error' && input_type == 'username') {
                        $('#User_username').closest('.form-group').find('.error').html(resp.msg);
                    }else{
                        $('#User_username').closest('.form-group').find('.error').html('');
                    }
                    if (resp.type == 'error' && input_type == 'email') {
                        $('#User_email').closest('.form-group').find('.error').html(resp.msg);
                    }else{
                        $('#User_email').closest('.form-group').find('.error').html('');
                    }
                });
                request.fail(function (jqXHR, textStatus) {
                    alert("Request failed: " + textStatus);
                });
            }
        });
        $('#submit_btn').on('click', function (e) {
            var request = $.ajax({
                url: $('form').attr('action'),
                method: "POST",
                data: $('form').serialize(),
                dataType: "json"
            });
            request.done(function (resp) {
                if (resp.type == 'error' && resp.input_type == 'username') {
                    $('#User_username').closest('.form-group').find('.error').html(resp.msg);
                }
                if (resp.type == 'error' && resp.input_type == 'email') {
                    $('#User_email').closest('.form-group').find('.error').html(resp.msg);
                }
                if (resp.type == 'success') {
                    $('#success_div').show();
                    $('#success_div').html(resp.msg);
                    $('html, body').animate({ scrollTop: 0 }, 'slow', function () { });
                }
            });
            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
        });
    });

</script>

@endsection
