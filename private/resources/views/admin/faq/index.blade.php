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
            <span>Faqs</span>
        </li>
    </ul>    
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Manage Faqs</h1>
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
            <i class="fa fa-list"></i>Faqs Listing 
        </div>        
        <div class="pull-right">
            <a href="{{ URL::to('admin/addFaq') }}" class="btn btn-primary">+ Add Faq</a>
        </div>
    </div>
    <div class="portlet-body flip-scroll">
        <table id="faq-table" class="table table-bordered table-striped table-condensed flip-content">
            <thead class="flip-content">
                <tr>
                    <th> Id </th>
                    <th> question </th>
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
        pageDatatable();
    });

    $(document).on('click', '.item-remove', function (e) {
        console.log('asd');
        e.preventDefault();
        var _this = $(this);
        var request = $.ajax({
            url: "{{ URL::to('admin/removeFaq') }}" + '?id=' + _this.data('id'),
            method: "POST",
            dataType: "json"
        });
        request.done(function (resp) {
            if (resp.type == 'success') {
                var table = $('#faq-table').DataTable();
                table.destroy();
                pageDatatable();
            }
        });
    });

    function pageDatatable() {
        $('#faq-table').DataTable({
            serverSide: true,
            processing: true,
            ajax: '<?= SITEURL('admin/manageFaqs-data') ?>',
            columns: [
                {data: 'id'},
                {data: 'question'},
                {data: 'created_at'},
                {data: 'updated_at'},
                {data: 'action', orderable: false, searchable: false}
            ]
        });
    }

</script>
@endsection
