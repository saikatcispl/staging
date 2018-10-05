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
<form role="form" id="siteSettings_form" method="post" action="<?php echo  URL::to('admin/siteSettings'); ?>">
    {{ csrf_field() }}    
    <div class="form-body">
        <div class="form-group form-md-line-input">
            {{Form::text('site_title', $model->site_title, array('class'=>"form-control",'id'=>"SiteSettings_site_title", 'placeholder'=> "Enter the Site Title."))}}
            <label for="form_control_1">Site Title</label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            {{Form::text('corp_name', $model->corp_name, array('class'=>"form-control",'id'=>"SiteSettings_corp_name", 'placeholder'=> "Enter the Corporate Name."))}}
            <label for="form_control_1">Corporate Name</label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            {{Form::textarea('corp_address', $model->corp_address, array('class'=>"form-control",'id'=>"SiteSettings_corp_address", 'placeholder'=> "Enter the Corporate Address."))}}
            <label for="form_control_1">Corporate Address</label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            {{Form::textarea('return_address', $model->return_address, array('class'=>"form-control",'id'=>"SiteSettings_return_address", 'placeholder'=> "Enter the Return Address."))}}
            <label for="form_control_1">Return Address</label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            {{Form::text('operating_hours', $model->operating_hours, array('class'=>"form-control",'id'=>"SiteSettings_operating_hours", 'placeholder'=> "Enter the Operating Hours."))}}
            <label for="form_control_1">Operating Hours</label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            {{Form::text('customer_support_email', $model->customer_support_email, array('class'=>"form-control",'id'=>"SiteSettings_customer_support_email", 'placeholder'=> "Enter the Customer Support Email."))}}
            <label for="form_control_1">Customer Support Email</label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            {{Form::text('customer_support_no', $model->customer_support_no, array('class'=>"form-control",'id'=>"SiteSettings_customer_support_no", 'placeholder'=> "Enter the Customer Support Number."))}}
            <label for="form_control_1">Customer Support No</label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            {{Form::text('fb_url', $model->fb_url, array('class'=>"form-control",'id'=>"SiteSettings_fb_url", 'placeholder'=> "Enter the Facebook link URL."))}}
            <label for="form_control_1">Facebook link URL</label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            {{Form::text('twitter_url', $model->twitter_url, array('class'=>"form-control",'id'=>"SiteSettings_twitter_url", 'placeholder'=> "Enter the Twitter link URL."))}}
            <label for="form_control_1">Twitter link URL</label>
            <span class="error"></span>
        </div>
        
    </div>
    <div class="form-actions noborder">
        <button type="submit" id="submit_btn" class="btn blue">Save</button>
        <a href="{{ URL::to('admin/dashboard') }}" class="btn default">Cancel</a>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
// $(document).ready(function () {
//     $('#siteSettings_form').validate({// initialize the plugin
//         rules: {
//             question: {
//                 required: true
//             },
//             answer: {
//                 required: true
//             }
//         },
//         messages: {
//             question: "Question can not be blank!",
//             answer: "Answer can not be blank!"
//         },
//         errorElement: 'div',
//         errorLabelContainer: '.errorTxt'
//     });
// });

</script>

