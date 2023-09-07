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
                bProcessing: true,
                bServerSide: true,
                ajax: {
                    url: "api/be/doctor-schedule-date",
                    headers: {
                        "Authorization": "{{ session('access_token') }}"
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'schedulDate',
                        name: 'schedulDate'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
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
                        type: "DELETE",
                        url: `api/be/doctor-schedule-date/${id}`,
                        headers: {
                            "Authorization": "{{ session('access_token') }}"
                        },
                        data: {
                            "_token": token
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                swal({
                                    title: 'Deleted!',
                                    text: 'user has been deleted.',
                                    type: 'success',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    timer: 1500
                            })
                            setTimeout(function() {
                                window.location.reload(true);
                            }, 2000);
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
                <span class="caption-subject font-blue sbold uppercase">CMS Doctor Schedule Date List</span>
            </div>
        </div>
        <div class="portlet-body">
            <a href="{{ route('doctor-schedule-date.create') }}" class="btn btn-success btn_add_user" style="margin-bottom: 15px;">
                <i class="fa fa-plus"></i> Add New Schedule Date
            </a>
            <table class="table trace trace-as-text table-striped table-bordered table-hover dt-responsive" id="table_data">
                <thead class="trace-head">
                    <tr>
                        <th style="text-align: center"> No </th>
                        <th style="text-align: center"> Schedule Period </th>
                        <th style="text-align: center"> Date </th>
                        <th style="text-align: center"> Action </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
