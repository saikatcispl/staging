@extends('admin.layouts.app')
@section('content')

<style>
    .error{color: red;}
    .mediaimg{text-align:center;border: 2px solid #B2B2B2;padding-bottom: 10px;padding-top: 10px; margin:10px;}
    .mediaspan{margin-top: 12px;display: inherit;}
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
            <a href="{{ URL::to('admin/manageMembershipPackage') }}">Membership Package</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="<?php echo URL::to('admin/updateMembershipPackage') . '?id=' . $productModel->id; ?>">{{ $productModel->product_name }}</a>
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
                <div class="alert alert-success" id="success_div" style="display:none;"></div>
                <div class="alert alert-danger" id="error_div" style="display:none;"></div>
                <form role="form" id="manageMedia_form" class="dropzone" method="post" action="<?php echo URL::to('admin/uploadMembershipPackageMedia') . '?id=' . $productModel->id; ?>" enctype="multipart/form-data">
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
<div class="row">
    <div class="col-md-12 medialistDiv">
        <?php if (count($productModel->getMedia) > 0):
    foreach ($productModel->getMedia as $mediaKey => $media):
    ?>
					            <div class="col-md-3 mediaimg">
					                <img src="/backend/images/membershipPackage/{{$media->image}}" width="200">
					                <hr>
					                <span class="mediaspan">
					                    <a class="btn btn-danger mediaRemovebtn" data-id="{{$media->id}}">Remove</a>
					                </span>
					            </div>
					        <?php
endforeach;
endif;?>
    </div>
</div>

<script src="{{ asset('backend/assets/pages/scripts/dropzone.js') }}"></script>
<script>

Dropzone.options.myAwesomeDropzone = {

paramName: "file", // The name that will be used to transfer the file

maxFilesize: 2, // MB

clickable: true,
forceFallback: true,

acceptedFiles: "image/*",

// maxFiles: 1,

dictDefaultMessage: 'Add Membership Images and Media here. Drop files here to upload.',

addRemoveLinks: false,

accept: function (file, done, ) {

    done();

},

complete: function (file, response) {

    var resp = jQuery.parseJSON(response);

    // $('#noImage').hide();

    // $('#curr_image').remove();

    var imgname = resp.imgname;



    var imgpath = "/uploads/landingpageContent/" + imgname;

    var _html = '<div class="col-md-3 mediaimg">'+
                '<img src="/backend/images/membershipPackage/" width="200">'+
                '<hr>'+
                '<span class="mediaspan">'+
                '<a class="btn btn-danger mediaRemovebtn" data-id="">Remove</a>'+
                '</span></div>';

    $('.medialistDiv').prepend(_html);

    $('#success_div').html('Image added successfully');

    $('#success_div').show();

    $('#success_div').delay(7000).fadeOut(400);

}

};


$(document).on('click','.mediaRemovebtn', function(e){
e.preventDefault();
var _this = $(this);
$.ajax({
  method: "GET",
  url: "<?php echo URL::to('admin/removeMembershipPackageMedia') . '?id='; ?>"+_this.data('id'),
  dataType: "json",
  success: function(resp){
      if(resp.type == 'success'){
        _this.closest('.mediaimg').remove();
        $('#success_div').html(resp.msg);
        $('#success_div').show();
        $(window).scrollTop(0);
        $('#success_div').delay(7000).fadeOut(400);
      }
      if(resp.type == 'error'){
        $('#error_div').html(resp.msg);
        $('#error_div').show();
        $('#error_div').delay(7000).fadeOut(400);
      }
  }
});
});
</script>
@endsection
