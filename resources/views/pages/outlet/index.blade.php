@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var table=$('#table_data').DataTable({
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
                        null,
                        { orderable: false }
                    ],
                    lengthMenu: [
                        [5, 10, 15, 20, -1],
                        [5, 10, 15, 20, "All"]
                    ],
                    pageLength: 10
            });
        });

        var SweetAlert = function() {
            return {
                init: function() {
                    $(".sweetalert-delete").each(function() {
                        var token  	= "{{ csrf_token() }}";
                        let column 	= $(this).parents('tr');
                        let id     	= $(this).data('id');
                        let name    = $(this).data('name');
                        $(this).click(function() {
                            swal({
                                    title: "Are you sure want to delete "+name+"?",
                                    text: "Your will not be able to recover this data!",
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonClass: "btn-danger",
                                    confirmButtonText: "Yes, delete it!",
                                    closeOnConfirm: false
                                },
                                function(){
                                    $.ajax({
                                        type : "GET",
                                        url : "{{ url('outlet/delete') }}"+'/'+id,
                                        success : function(result) {
                                            if (result.status == "success") {
                                                swal({
                                                    title: 'Deleted!',
                                                    text: 'Outlet has been deleted.',
                                                    type: 'success',
                                                    showCancelButton: false,
                                                    showConfirmButton: false
                                                })
                                                SweetAlert.init()
                                                window.location.reload(true);
                                            } else if(result.status == "fail"){
                                                swal("Error!", result.messages[0], "error")
                                            } else {
                                                swal("Error!", "Something went wrong. Failed to delete vehicle brand.", "error")
                                            }
                                        }
                                    });
                                });
                        })
                    })
                }
            }
        }();

        jQuery(document).ready(function() {
            SweetAlert.init()
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
                <span class="caption-subject font-blue sbold uppercase">CMS Outlet List</span>
            </div>
        </div>
        <div class="portlet-body">
            <a href="{{ url('outlet/create') }}" class="btn btn-success btn_add_user" style="margin-bottom: 15px;">
                <i class="fa fa-plus"></i> Add New Outlet
            </a>
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                <thead>
                    <tr style="text-align: center">
                        <th style="text-align: center"> Name </th>
                        <th style="text-align: center"> Email </th>
                        <th style="text-align: center"> Phone </th>
                        <th style="text-align: center"> Status </th>
                        <th style="text-align: center"> Action </th>
                    </tr>
                </thead>
                <tbody id="">
                    @if (!empty($outlets))
                        @foreach ($outlets as $outlet)
                            <tr style="text-align: center;">
                                <td>{{ $outlet['name'] }}</td>
                                <td>{{ $outlet['outlet_email'] }}</td>
                                <td>{{ $outlet['outlet_phone'] }}</td>
                                <td>
                                    @if ($outlet['status'])
                                        <span class="badge badge-success badge-sm">Active</span>
                                    @else
                                        <span class="badge badge-danger badge-sm">Non-Active</span>
                                    @endif
                                </td>
                                <td style="width: 90px;">
                                    <a href="{{ url('outlet/detail') }}/{{ $outlet['id'] }}" class="btn btn-sm blue"><i class="fa fa-search"></i></a>
                                    <a class="btn btn-sm red sweetalert-delete btn-primary" data-id="{{ $outlet['id'] }}" data-name="{{ $outlet['name'] }}"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
