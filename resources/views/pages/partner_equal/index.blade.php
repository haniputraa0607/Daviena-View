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
                }
            }
        }();

        $(".btn-detail").each(function() {
            var token  	= "{{ csrf_token() }}";
            let id     	= $(this).data('id');
            let name    = $(this).data('name');
            $(this).click(function() {
                alert('sasa');
            })
        })

        jQuery(document).ready(function() {
            SweetAlert.init()
        });

        const main = {
            delete: function(that){
                let id = $(that).data('id');
                let name = $(that).data('name');
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
                        type : "delete",
                        url : "{{ url('partner_equal/delete') }}"+'/'+id,
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
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
            },
            detail: function(that){
                let id = $(that).data('id');
                let name = $(that).data('name');
                window.location.href = `{{ url('partner/detail') }}/${id}`;
            }
        }
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
                <span class="caption-subject font-blue sbold uppercase">CMS Partner List</span>
            </div>
        </div>
        <div class="portlet-body">
            <a href="{{ route('partner_equal.create') }}" class="btn btn-success btn_add_user" style="margin-bottom: 15px;">
                <i class="fa fa-plus"></i> Add Partner
            </a>
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                <thead>
                    <tr style="text-align: center">
                        <th style="text-align: center"> Name </th>
                        <th style="text-align: center"> Email </th>
                        <th style="text-align: center"> Phone </th>
                        <th style="text-align: center"> Action </th>
                    </tr>
                </thead>
                <tbody id="">
                    @if (!empty($partners))
                        @foreach ($partners as $partner)
                            <tr style="text-align: center;">
                                <td>{{ $partner['name'] }}</td>
                                <td>{{ $partner['email'] }}</td>
                                <td>{{ $partner['phone'] }}</td>
                                <td style="width: 90px;">                    
                                    <a data-id="{{ $partner['id'] }}" data-name="{{ $partner['name'] }}" class="btn btn-sm blue" onclick="main.detail(this)" href="{{ route('partner_equal.detail', $partner['id']) }}"><i class="fa fa-search"></i></a>
                                    <a class="btn btn-sm red sweetalert-delete btn-primary" data-id="{{ $partner['id'] }}" data-name="{{ $partner['name'] }}" onclick="main.delete(this)"><i class="fa fa-trash-o"></i></a>           
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

        </div>
    </div>

@endsection
