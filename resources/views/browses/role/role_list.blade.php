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
                        { orderable: false }
                    ],
                    lengthMenu: [
                        [5, 10, 15, 20, -1],
                        [5, 10, 15, 20, "All"]
                    ],
                    pageLength: 10
            });

            // var manual=1;
            // $('#role').on('switchChange.bootstrapSwitch','.status',function(){
            //     if(!manual){
            //         manual=1;
            //         return false;
            //     }
            //     var token  	= "{{ csrf_token() }}";
            //     var switcher=$(this);
            //     var newState="1";
            //     if (switcher.bootstrapSwitch('state') == true) {
            //         newState = "1"
            //     } else {
            //         newState = "0"
            //     }
            //     var id = switcher.data('id')

            //     $.ajax({
            //         type : "POST",
            //         url : "{{ url('browse/role/update/status') }}"+"/"+id,
            //         data:{
            //             _token:token,
            //             id:switcher.data('id'),
            //             status:newState
            //         },
            //         success:function(result){
            //             if(result.status == 'success'){
            //                 toastr.info("Success update vehicle brand visibility");
            //             }else{
            //                 manual=0;
            //                 toastr.warning("Fail update vehicle brand visibility");
            //                 switcher.bootstrapSwitch('state',!newState);
            //             }
            //         }
            //     })
            // });
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
                <span class="caption-subject font-blue sbold uppercase">Role List</span>
            </div>
        </div>
        <div class="portlet-body">
            <a href="#modalAddRole" class="btn btn-success btn_add_type" data-toggle="modal" style="margin-bottom: 15px;">
                <i class="fa fa-plus"></i> Add New Role
            </a>
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                <thead>
                    <tr style="text-align: center">
                        <th style="text-align: center"> Name </th>
                        <th style="text-align: center"> Status </th>
                        <th style="text-align: center"> Action </th>
                    </tr>
                </thead>
                <tbody id="role">
                    @if (!empty($roles))
                        @foreach ($roles as $role)
                            <tr style="text-align: center">
                                <td>{{ $role['name'] }}</td>
                                {{-- <td><input type="checkbox" class="make-switch status" data-size="small" data-on-color="info" data-on-text="Active" data-off-color="default" data-id="{{$role['id']}}" data-off-text="Nonactive" value="1" @if($role['status']??'') checked @endif></td> --}}
                                <td>
                                    @if ($role['status'])
                                        <span class="badge badge-success badge-sm">Active</span>
                                    @else
                                        <span class="badge badge-danger badge-sm">Non-Active</span>
                                    @endif
                                </td>
                                <td style="width: 90px;">
                                    <a href="{{ url('browse/role') }}/{{ $role['id'] }}" class="btn btn-sm blue"><i class="fa fa-search"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="modalAddRole" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add Role</h4>
                </div>
                <div class="modal-body form">
                    <div class="form-body">
                    <form role="form" action="{{ url('browse/role/add') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name<span class="required" aria-required="true">*</span>
                                    <i class="fa fa-question-circle tooltips" data-original-title="Role Name" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="text" placeholder="Role Name" class="form-control" name="name" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Status<span class="required" aria-required="true">*</span>
                                    <i class="fa fa-question-circle tooltips" data-original-title="Role Status" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="checkbox" class="make-switch" data-size="small" data-on-color="info" data-on-text="Active" data-off-color="default" data-off-text="Nonactive" name="status" value="1" checked>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center" style="padding: 10px">
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
