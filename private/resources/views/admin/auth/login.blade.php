@extends('admin.layouts.login')
@section('content')
<style>
    .error{color:red;}
</style>
<div class="user-login-5">
    <div class="row bs-reset">
        <div class="col-md-6 bs-reset mt-login-5-bsfix">
            <div class="login-bg" style="background-image:url({{ asset('backend/assets/pages/img/login/bg1.jpg') }})"></div>
        </div>
        <div class="col-md-6 login-container bs-reset mt-login-5-bsfix">
            <div class="login-content">
                <h1>{{ config('app.name') }} Admin Login</h1>
                <p> Lorem ipsum dolor sit amet, coectetuer adipiscing elit sed diam nonummy et nibh euismod aliquam erat volutpat. Lorem ipsum dolor sit amet, coectetuer adipiscing. </p>
                <form action="{{ URL::route('admin.login') }}" name="form_login"  class="login-form" id="login-form" method="post">
                    @if (!empty('invalid_account_token_message') && Session::has('invalid_account_token_message'))
                    <div class="alert alert-warning">
                        {{Session::get('invalid_account_token_message')}}
                    </div>
                    @endif
                    @if (!empty('valid_account_token_message') && Session::has('valid_account_token_message'))
                    <div class="alert alert-success">
                        {{Session::get('valid_account_token_message')}}
                    </div>
                    @endif
                    {{ csrf_field() }}
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        <span>Enter any username and password. </span>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="Username" name="username" /> 
                            <!--                            @if ($errors->has('email'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                        @endif-->
                        </div>
                        <div class="col-xs-6">
                            <input class="form-control form-control-solid placeholder-no-fix form-group" type="password" autocomplete="off" placeholder="Password" name="password" /> 
                            <!--                            @if ($errors->has('password'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                        @endif-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button class="btn green" type="submit">Sign In</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="login-footer">
                <div class="col-xs-7 bs-reset">
                    <div class="login-copyright text-right">
                        <p>Copyright &copy; {{ config('app.name') }} {{ Date('Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('backend/assets/pages/auth/login.js') }}" type="text/javascript"></script>
<!--<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>-->
<script>
//$(document).ready(function () {
//    $('#login-form').validate({// initialize the plugin
//        rules: {
//            username: {
//                required: true
//            },
//            password: {
//                required: true
//            }
//        },
//        messages: {
//            username: "Username can not be blank!",
//            password: "Password can not be blank!",
//        }
//    });
//});
</script>
@endsection