@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/datatables/datatables.min.css' }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-sweetalert/sweetalert.css' }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-toastr/toastr.min.css' }}" rel="stylesheet"
        type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/scripts/datatable.js' }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/datatables/datatables.min.js' }}"
        type="text/javascript"></script>
    <script
        src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}"
        type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js' }}"
        type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-toastr/toastr.min.js' }}"
        type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#table_data').DataTable({
                language: {
                    aria: {
                        sortAscending: ": activate to sort column ascending",
                        sortDescending: ": activate to sort column descending"
                    },
                    emptyTable: "No data available in table",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No entries found",
                    infoFiltered: "(filtered1 from _MAX_ total entries)",
                    lengthMenu: "_MENU_ entries",
                    search: "Search:",
                    zeroRecords: "No matching records found"
                },
                responsive: {
                    details: {
                        type: "column",
                        target: "tr"
                    }
                },
                order: [],
                columns: [
                    null,
                    null,
                    null,
                    {
                        orderable: false
                    }
                ],
                lengthMenu: [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"]
                ],
                pageLength: 10
            });
        });

        $('body').on('click', '#btn-delete', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let token = $("meta[name='csrf-token']").attr("content");

            swal({
                    title: "Are you sure want to delete " + name + "?",
                    text: "Your will not be able to recover this data!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function() {
                    $.ajax({
                        type: "POST",
                        url: `/user/delete/${id}`,
                        data: {
                            "_token": token
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                swal({
                                    title: 'Deleted!',
                                    text: 'User has been deleted.',
                                    type: 'success',
                                    showCancelButton: false,
                                    showConfirmButton: false
                                })
                                window.location.reload(true);
                            } else if (response.status == "fail") {
                                swal("Error!", response.messages[0], "error")
                            } else {
                                swal("Error!",
                                    "Something went wrong. Failed to delete vehicle brand.",
                                    "error")
                            }
                        }
                    }); 

                });
        });
    </script>
@endsection

@section('content')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>{{ $title }}</span>
                @if (!empty($sub_title))
                    <i class="fa fa-circle"></i>
                @endif
            </li>
            @if (!empty($sub_title))
                <li>
                    <span>{{ $sub_title }}</span>
                </li>
            @endif
        </ul>
    </div><br>

    @include('layouts.notifications')

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue sbold uppercase">CMS User List</span>
            </div>
        </div>
        <div class="portlet-body">
            <a href="{{ url('user/create') }}" class="btn btn-success btn_add_user" style="margin-bottom: 15px;">
                <i class="fa fa-plus"></i> Add New User
            </a>
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                <thead>
                    <tr style="text-align: center">
                        <th style="text-align: center"> Name </th>
                        <th style="text-align: center"> Idc </th>
                        <th style="text-align: center"> Email </th>
                        <th style="text-align: center"> Phone </th>
                        <th style="text-align: center"> Type </th>
                        <th style="text-align: center"> Action </th>
                    </tr>
                </thead>
                <tbody id="">
                    @if (!empty($cms_users))
                        @foreach ($cms_users as $user)
                            <tr style="text-align: center; {{ $user['type'] == 'super_admin' ? 'color:red;' : '' }}">
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['idc'] }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td>{{ $user['phone'] }}</td>
                                <td>{{ $user['type'] }}</td>
                                <td style="width: 90px;">
                                    <a href="{{ url('user/detail/' . $user['id']) }}" class="btn btn-sm blue"><i
                                            class="fa fa-search"></i></a>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger" id="btn-delete"
                                        data-id="{{ $user['id'] }}" data-name="{{ $user['name'] }}"><i
                                            class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
