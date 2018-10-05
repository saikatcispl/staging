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
            <span>Coupons</span>
        </li>
    </ul>    
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Manage Coupons</h1>
<!-- END PAGE TITLE-->

@if(Session::has('success'))
<p class="alert alert-success">{{ Session::get('success') }}</p>
@endif
@if(Session::has('error'))
<p class="alert alert-danger">{{ Session::get('error') }}</p>
@endif
<!-- BEGIN SAMPLE TABLE PORTLET-->
<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-list"></i>Coupons Listing 
        </div>        
        <div class="pull-right">
            <a href="{{ URL::to('admin/addCoupon') }}" class="btn btn-primary">+ Add Coupon</a>
        </div>
    </div>
    <div class="portlet-body flip-scroll">
        <table id="coupon-table" class="table table-bordered table-striped table-condensed flip-content">
            <thead class="flip-content">
                <tr>
                    <th> Id </th>
                    <th> Name </th>
                    <th> Code </th>
                    <th> Created At </th>
                    <th> Updated At </th>
                    <th> Action </th>
                </tr>
            </thead>            
        </table>
    </div>
</div>
<!-- END SAMPLE TABLE PORTLET-->

<!-- /.modal -->
<div class="modal fade bs-modal-lg" id="responsive" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" id="modalTitle" style="display:none;">Assign Product(s) To Coupon(ID#<span id="coupon_id"></span>): <span id="coupon_title"></span></h4>
            </div>
            <div class="modal-body" style="height: 400px;">
                <div class="alert alert-success" id="success_div" style="display:none;"></div>
                <div class="alert alert-danger" id="error_div" style="display:none;"></div>
                <form role="form" id="product_assign_form" method="post" action="{{ URL::to('admin/assignToProducts') }}">
                    <input type="hidden" name="coupon_id" id="coupon_id" value="">
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="form-group form-md-line-input">
                            <div id="productList"></div>
                            <label for="form_control_1">Products Listing<span class="required">*</span></label>
                            <span class="error"></span>
                        </div>
                    </div>                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                <button type="button" class="btn green" id="submit_btn">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
    $(document).on('click', '.assignToProduct', function (e) {
        e.preventDefault();
        var _this = $(this);
        var couponId = _this.data('cid');

        $('#error_div').hide();
        $('#success_div').hide();
        $('#error_div').html('');
        $('#success_div').html('');

        var request = $.ajax({
            url: "{{ URL::to('admin/generateCouponsView') }}" + '?cid=' + couponId,
            method: "GET",
            dataType: "json"
        });
        request.done(function (resp) {
            if (resp.type == 'error') {
                $('#error_div').html(resp.msg);
                $('#error_div').show();
            }
            if (resp.type == 'success') {
                $('#preModalTitle').hide();
                $('input[name=coupon_id]').val(resp.couponId);
                $('#coupon_id').html(resp.couponId);
                $('#coupon_title').html(resp.couponName);
                $('#modalTitle').show();
                $('#productList').html(resp.couponDropDown);
                $('html, body').animate({scrollTop: 0}, 'slow', function () {
                });
            }
        });
        request.fail(function (jqXHR, textStatus) {
            $('#error_div').html("Request failed: " + textStatus);
            $('#error_div').show();
        });
    });

    $(document).on('click', '#submit_btn', function (e) {
        e.preventDefault();
        var _this = $(this);
        $('#error_div').hide();
        $('#success_div').hide();
        $('#error_div').html('');
        $('#success_div').html('');

        var request = $.ajax({
            url: "{{ URL::to('admin/assignToProducts') }}",
            method: "POST",
            data: $('form').serialize(),
            dataType: "json"
        });
        request.done(function (resp) {
            if (resp.type == 'error') {
                $('#error_div').html(resp.msg);
                $('#error_div').show();
            }
            if (resp.type == 'success') {
                $('#success_div').html(resp.msg);
                $('#success_div').show();
                $('html, body').animate({scrollTop: 0}, 'slow', function () {
                });
            }
        });
        request.fail(function (jqXHR, textStatus) {
            $('#error_div').html("Request failed: " + textStatus);
            $('#error_div').show();
        });
    });


    $(function () {
        $('#coupon-table').DataTable({
            serverSide: true,
            processing: true,
            ajax: '<?= SITEURL('admin/manageCoupons-data') ?>',
            columns: [
                {data: 'id'},
                {data: 'name'},
                {data: 'code'},
                {data: 'created_at'},
                {data: 'updated_at'},
                {data: 'action', orderable: false, searchable: false}
            ]
        });
    });

    $(document).on('click', '.item-remove', function (e) {
        console.log('asd');
        e.preventDefault();
        var _this = $(this);
        var request = $.ajax({
            url: "{{ URL::to('admin/removeCoupon') }}" + '?cid=' + _this.data('cid'),
            method: "POST",
            dataType: "json"
        });
        request.done(function (resp) {
            if (resp.type == 'success') {
                var table = $('#coupon-table').DataTable();
                table.destroy();
                $('#coupon-table').DataTable({
                    serverSide: true,
                    processing: true,
                    ajax: '<?= SITEURL('admin/manageCoupons-data') ?>',
                    columns: [
                        {data: 'id'},
                        {data: 'name'},
                        {data: 'code'},
                        {data: 'created_at'},
                        {data: 'updated_at'},
                        {data: 'action', orderable: false, searchable: false}
                    ]
                });
            }
        });
    });

</script>
@endsection
