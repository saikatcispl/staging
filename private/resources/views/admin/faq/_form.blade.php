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
<form role="form" id="coupon_form" method="post" action="<?php echo $isNew == 'yes' ? URL::to('admin/addFaq') : URL::to('admin/updateFaq').'?id='.$faqModel->id; ?>">
    {{ csrf_field() }}
    <?php
    if ($isNew == 'no'):
        ?>
        <input type="hidden" name="id" value="<?php echo $faqModel->id; ?>">
    <?php endif; ?>
    <div class="form-body">
        <div class="form-group form-md-line-input">
            {{Form::text('question', $faqModel->question, array('class'=>"form-control",'id'=>"Faq_question", 'placeholder'=> "Enter the question here."))}}
            <label for="form_control_1">Question<span class="required">*</span></label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            {{Form::textarea('answer',$faqModel->answer, array('class'=>"form-control",'id'=>"Coupon_code", 'placeholder'=> "Enter the answer here.."))}}
            <label for="form_control_1">Answer<span class="required">*</span></label>
            <span class="error"></span>
        </div>
    </div>
    <div class="form-actions noborder">
        <button type="submit" id="submit_btn" class="btn blue"><?php echo $isNew == 'yes' ? 'Create' : 'Save'; ?></button>
        <a href="{{ URL::to('admin/manageFaqs') }}" class="btn default">Cancel</a>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
$(document).ready(function () {
    $('#coupon_form').validate({// initialize the plugin
        rules: {
            question: {
                required: true
            },
            answer: {
                required: true
            }
        },
        messages: {
            question: "Question can not be blank!",
            answer: "Answer can not be blank!"
        },
        errorElement: 'div',
        errorLabelContainer: '.errorTxt'
    });
});

</script>

