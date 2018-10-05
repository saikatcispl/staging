@extends('admin.layouts.app')
@section('content')
<!-- BEGIN SAMPLE TABLE PORTLET-->
<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-list"></i>Users Listing </div>        
    </div>
    <div class="portlet-body flip-scroll">
        <table id="users-table" class="table table-bordered table-striped table-condensed flip-content">
            <thead class="flip-content">
                <tr>
                    <th> Id </th>
                    <th> User Type </th>
                    <th> Username </th>
                    <th> Email </th>
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
        $('#users-table').DataTable({
            serverSide: true,
            processing: true,
            ajax: '<?= SITEURL('admin/users-data') ?>',
            columns: [
                {data: 'id'},
                {data: 'role_id'},
                {data: 'username'},
                {data: 'email'},
                {data: 'created_at'},
                {data: 'updated_at'},
                {data: 'action', orderable: false, searchable: false}
            ]
        });
    });
    
    
//     $(function () {
//        $('#users-table').DataTable({
//            serverSide: true,
//            processing: true,
//            ajax: '<?= SITEURL('admin/users-data') ?>',
//            columns: [
//                {data: 'id'},
//                {data: 'role_id', render: function (data, type, row, meta) {
//                        var usertype = '';
//                         if (data == 2) {
//                                usertype = 'Admin';
//                            }
//                            if (data == 3) {
//                                usertype = 'Distributor';
//                            }
//                            if (data == 4) {
//                                usertype = 'Customer';
//                            }
//                            return usertype;
//                    }},
//                {data: 'username'},
//                {data: 'email'},
//                {data: 'created_at'},
//                {data: 'updated_at'},
//                {data: 'action', orderable: false, searchable: false}
//            ]
//        });
//    });
</script>
@endsection
