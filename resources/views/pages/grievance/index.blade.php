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
            const fetchButton = $('#fetchButton');
            const responseDiv = $('#response');

            fetchButton.on('click', function() {
                const url = 'http://localhost:8002/api/test';

                // Make the AJAX request using jQuery

                // Add your headers
                const headers = {
                    "Authorization": 'Token 04f7a4e33a692196c39ef9ba54c53459c9a9b25b',
                    'Access-Control-Allow-Origin': '*',
                    'Access-Control-Allow-Headers': 'Origin,X-Requested-With',
                    'Accept': 'application/json',
                    'Access-Control-Allow-Methods': 'POST',
                    'Access-Control-Allow-Credentials': 'true'
                };

                // Make the AJAX request using jQuery
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    // headers: headers,
                    success: function(data) {
                        // Process the response data
                        console.log(data);
                        // responseDiv.html(JSON.stringify(data, null, 2));
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            });
        });
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
                        url: `/grievance/delete/${id}`,
                        data: {
                            "_token": token
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                swal({
                                    title: 'Deleted!',
                                    text: 'Grievances has been deleted.',
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
                <span class="caption-subject font-blue sbold uppercase">CMS Grievance List</span>
            </div>
        </div>
        <div class="portlet-body">
            <a href="{{ url('grievance/create') }}" class="btn btn-success" style="margin-bottom: 15px;">
                <i class="fa fa-plus"></i> Add New Grievance
            </a>
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                <thead>
                    <tr style="text-align: center">
                        <th style="text-align: center"> Name </th>
                        <th style="text-align: center"> Description </th>
                        <th style="text-align: center"> Status </th>
                        <th style="text-align: center"> Action </th>
                    </tr>
                </thead>
                <tbody id="">
                    @if (!empty($grievances))
                        @foreach ($grievances as $grievance)
                            <tr style="text-align: left;">
                                <td>{{ $grievance['grievance_name'] }}</td>
                                <td>{{ substr($grievance['description'], 0, 100) }}</td>
                                <td style="text-align: center;">
                                    @if ($grievance['is_active'] == '1')
                                        <span class="badge badge-success badge-sm">Active</span>
                                    @else
                                        <span class="badge badge-danger badge-sm">Non-Active</span>
                                    @endif
                                </td>
                                <td style="width: 120px; text-align: center;">
                                    <a href="{{ url('grievance/detail') }}/{{ $grievance['id'] }}"
                                        class="btn btn-sm blue"><i class="fa fa-search"></i></a>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger" id="btn-delete"
                                        data-id="{{ $grievance['id'] }}" data-name="{{ $grievance['grievance_name'] }}"><i
                                            class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <button id="fetchButton">Fetch Data</button>
        <div id="response"></div>
    </div>

@endsection
