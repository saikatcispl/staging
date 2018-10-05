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
            <span>Membership Packages</span>
        </li>
    </ul>    
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Manage Membership Packages</h1>
<!-- END PAGE TITLE-->

@if(Session::has('success'))
<p class="alert alert-success">{{ Session::get('success') }}</p>
@endif
@if(Session::has('error'))
<p class="alert alert-danger">{{ Session::get('error') }}</p>
@endif

<div class="alert alert-success" id="success_div" style="display:none;"></div>
<div class="alert alert-danger" id="error_div" style="display:none;"></div>

<!-- BEGIN SAMPLE TABLE PORTLET-->
<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-list"></i>Membership Packages Listing 
        </div>        
        <div class="pull-right">
            <a href="{{ URL::to('admin/addMembershipPackage') }}" class="btn btn-primary" title="Add Membership Packages(LL Product ID) to sync from LimeLight CRM.">+ Add Product</a>
        </div>
    </div>
    <div class="portlet-body flip-scroll">
        <table id="product-table" class="table table-bordered table-striped table-condensed flip-content">
            <thead class="flip-content">
                <tr>
                    <th> ID </th>
                    <th> LL Product ID </th>
                    <th> Name </th>
                    <th> SKU ID </th>
                    <th> Category </th>
                    <th> Price </th>
                    <th> Created At </th>
                    <th> Updated At </th>
                    <th> Action </th>
                </tr>
            </thead>            
        </table>
    </div>
</div>
<!-- END SAMPLE TABLE PORTLET-->

<script>
    $(function () {
        $('#product-table').DataTable({
            serverSide: true,
            processing: true,
            ajax: '<?= SITEURL('admin/manageMembershipPackage-data') ?>',
            columns: [
                {data: 'id'},
                {data: 'll_product_id'},
                {data: 'product_name'},
                {data: 'product_sku'},
                {data: 'product_category_name'},
                {data: 'product_price'},
                {data: 'created_at'},
                {data: 'updated_at'},
                {data: 'action', orderable: false, searchable: false}
            ]
        });
    });
    $(document).on('click', '.manuallySyncProductFromLL', function (e) {
        e.preventDefault();
        var _this = $(this);
        var request = $.ajax({
            url: "{{ URL::to('admin/manuallySyncMembershipPackageFromLL') }}" + '?ll_product_id=' + _this.data('ll_product_id'),
            method: "GET",
            dataType: "json"
        });
        request.done(function (resp) {
            if (resp.type == 'success') {
                $('#success_div').html(resp.msg);
                $('#success_div').show();
                var table = $('#product-table').DataTable();
                table.destroy();
                $('#product-table').DataTable({
                    serverSide: true,
                    processing: true,
                    ajax: '<?= SITEURL('admin/manageMembershipPackage-data') ?>',
                    columns: [
                        {data: 'id'},
                        {data: 'll_product_id'},
                        {data: 'product_name'},
                        {data: 'product_sku'},
                        {data: 'product_category_name'},
                        {data: 'product_price'},
                        {data: 'created_at'},
                        {data: 'updated_at'},
                        {data: 'action', orderable: false, searchable: false}
                    ]
                });

            } else {
                $('#error_div').html(resp.msg);
                $('#error_div').show();
            }
        });
        request.fail(function (jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
        });
    });
</script>
@endsection
