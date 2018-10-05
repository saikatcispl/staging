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
<form role="form" id="cms_form" method="post" action="<?php echo $isNew == 'yes' ? URL::to('admin/addCms') : URL::to('admin/updateCms') . '?id=' . $cmsModel->id; ?>">
    {{ csrf_field() }}
    <?php
if ($isNew == 'no'):
?>
        <input type="hidden" name="id" value="<?php echo $cmsModel->id; ?>">
    <?php endif;?>
    <div class="form-body">
        <div class="form-group form-md-line-input">
            {{Form::text('name', $cmsModel->name, array('class'=>"form-control",'id'=>"Cms_name", 'placeholder'=> "Enter the name here."))}}
            <label for="form_control_1">Name<span class="required">*</span></label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            {{Form::text('slug', $cmsModel->slug, array('class'=>"form-control",'id'=>"Cms_slug",'disabled'=>$isNew == 'no'?'disabled':'', 'placeholder'=> "Enter the slug here."))}}
            <label for="form_control_1">Slug<span class="required">*</span></label>
            <span class="error"></span>
        </div>
        <div class="form-group form-md-line-input">
            {{Form::textarea('content',$cmsModel->content, array('class'=>"form-control",'id'=>"Coupon_content", 'placeholder'=> "Write the content here.."))}}
            <label for="form_control_1">Content<span class="required">*</span></label>
            <span class="error"></span>
        </div>
    </div>
    <div class="form-actions noborder">
        <button type="submit" id="submit_btn" class="btn blue"><?php echo $isNew == 'yes' ? 'Create' : 'Save'; ?></button>
        <a href="{{ URL::to('admin/manageCms') }}" class="btn default">Cancel</a>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script src="https://cdn.ckeditor.com/4.10.0/full/ckeditor.js"></script>
<script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'Coupon_content' );
                CKEDITOR.editorConfig = function( config ) {
	config.toolbar = [
		{ name: 'document', items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
		{ name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
		{ name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
		{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
		'/',
		{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
		{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
		{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
		{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
		'/',
		{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
		{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
		{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
		{ name: 'about', items: [ 'About' ] }
	];
};
            </script>
            <?php
            if($isNew == 'yes'):
            ?>
<script>
$(document).ready(function () {
    $('#cms_form').validate({// initialize the plugin
        rules: {
            name: {
                required: true
            },
            slug: {
                required: true
            },
            content: {
                required: true
            }
        },
        messages: {
            name: "Name can not be blank!",
            slug: "Slug can not be blank!",
            content: "Content can not be blank!"
        },
        errorElement: 'div',
        errorLabelContainer: '.errorTxt'
    });
});

</script>
<?php else:?>
<script>
$(document).ready(function () {
    $('#cms_form').validate({// initialize the plugin
        rules: {
            name: {
                required: true
            }
            content: {
                required: true
            }
        },
        messages: {
            name: "Name can not be blank!",
            content: "Content can not be blank!"
        },
        errorElement: 'div',
        errorLabelContainer: '.errorTxt'
    });
});

</script>

<?php endif;?>

