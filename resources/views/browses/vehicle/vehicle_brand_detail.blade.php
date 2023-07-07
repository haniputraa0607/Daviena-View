@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .tooltip {
            z-index: 100000000;
        }
    </style>
@endsection


@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
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
                                            url : "{{ url('browse/vehicle/type/delete') }}"+'/'+id,
                                            success : function(result) {
                                                if (result.status == "success") {
                                                    swal({
                                                        title: 'Deleted!',
                                                        text: 'Vehicle type has been deleted.',
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

        $(document).ready(function() {
            $(".file").change(function(e) {
                var _URL = window.URL || window.webkitURL;
                var image, file;

                if ((file = this.files[0])) {
                    image = new Image();

                    image.onload = function() {
                        if ($(".file").val().split('.').pop().toLowerCase() != 'png') {
                            toastr.warning("Please check type of your logo.");
                            $("#removeLogo").trigger( "click" );
                        }
                        if (this.width != 200 || this.height != 200) {
                            toastr.warning("Please check dimension of your logo.");
                            $("#removeLogo").trigger( "click" );
                        }
                    };
                    image.src = _URL.createObjectURL(file);
                }
            });

            var manual=1;
            $('#type').on('switchChange.bootstrapSwitch','.type_visibility',function(){
                if(!manual){
                    manual=1;
                    return false;
                }
                var token  	= "{{ csrf_token() }}";
                var switcher=$(this);
                var newState="1";
                if (switcher.bootstrapSwitch('state') == true) {
                    newState = "1"
                } else {
                    newState = "0"
                }
                var id = switcher.data('id')

                $.ajax({
                    type : "POST",
                    url : "{{ url('browse/vehicle/type/update/visibility') }}"+"/"+id,
                    data:{
                        _token:token,
                        id:switcher.data('id'),
                        visibility:newState
                    },
                    success:function(result){
                        if(result.status == 'success'){
                            toastr.info("Success update vehicle type visibility");
                        }else{
                            manual=0;
                            toastr.warning("Fail update vehicle type visibility");
                            switcher.bootstrapSwitch('state',!newState);
                        }
                    }
                })
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#per_page').on('change', function () {
                let perPageValue = $(this).val();
                let currentUrl = window.location.href;
                let url = new URL(currentUrl);
                url.searchParams.set('per_page', perPageValue);
                window.location.href = url.toString();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#modalUpdateType').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var id = button.data('id') // Extract info from data-* attributes
                var name = button.data('name') // Extract info from data-* attributes
                var ac = button.data('ac') // Extract info from data-* attributes
                var dc = button.data('dc') // Extract info from data-* attributes
                var modal = $(this)

                //clear modal first
                modal.find('#update-vehicle-type').attr("action", null)
                modal.find('#type_id_update').val(null)
                modal.find('#type_name_update').val(null)
                modal.find('#type_ac_max_update').val(null)
                modal.find('#type_dc_max_update').val(null)

                var url = "{{ url('browse/vehicle/type/update') }}/"+id

                modal.find('#update-vehicle-type').attr("action", url)
                modal.find('#type_id_update').val(id)
                modal.find('#type_name_update').val(name)
                if (ac != 0) {
                    modal.find('#type_ac_max_update').val(ac)
                }

                if (dc != 0) {
                    modal.find('#type_dc_max_update').val(dc)
                }
            })
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#filter-type').change(function() {
                var selectedType = $(this).val();

                // Hide all filter values
                $('#filter-value select, #filter-value input').hide();

                // Show the selected filter value
                if (selectedType === 'user') {
                    var searchQueryParam = new URLSearchParams(window.location.search).get('user');
                    $('#filter-value input#search').show();
                    $('#filter-value input#search').val(searchQueryParam || '');
                } else if (selectedType === 'type') {
                    $('#filter-value select#type').show();
                } else if (selectedType === 'status') {
                    $('#filter-value select#status').show();
                }
            });

            $('#filter-type').trigger('change');

            // Filter button click event
            $('.filter-button').click(function() {
                var filterType = $('#filter-type').val();
                var filterValue = $('#filter-value').find(':visible').val();

                // Remove existing query parameters
                var url = new URL(window.location.href);
                url.searchParams.delete('user');
                url.searchParams.delete('type');
                url.searchParams.delete('status');

                // Add the new query parameter
                if (filterType === 'user' && filterValue) {
                    url.searchParams.set('user', filterValue);
                } else if (filterType === 'type' && filterValue) {
                    url.searchParams.set('type', filterValue);
                } else if (filterType === 'status' && filterValue) {
                    url.searchParams.set('status', filterValue);
                }

                // Redirect to the new URL
                window.location.href = url.toString();
            });

            $('.filter-reset').click(function() {
                // Remove existing query parameters
                var url = new URL(window.location.href);
                url.searchParams.delete('user');
                url.searchParams.delete('type');
                url.searchParams.delete('status');

                // Redirect to the new URL
                window.location.href = url.toString();
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
                <span class="caption-subject font-dark sbold uppercase font-blue">{{$sub_title}}</span>
            </div>
        </div>
        <div class="portlet-body form">
            <form class="form-horizontal" role="form" action="{{ url()->current() }}" method="post" enctype="multipart/form-data">
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">
                            Logo <i class="fa fa-question-circle tooltips" data-original-title="Gambar dengan ukuran square digunakan utnuk menjadi logo brand" data-container="body"></i>
                            <br>
                            <span class="required" aria-required="true"> (200*200)</span>
                            <br>
                            <span class="required" aria-required="true"> (Only PNG) </span>
                        </label>
                        <div class="col-md-7">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;">
                                    @if(isset($detail['logo']) && $detail['logo'] != "")
                                        <img src="{{ env('STORAGE_URL_API')}}{{$detail['logo']}}" id="preview_logo_brand" />
                                    @else
                                        <img id="preview_logo_brand" src="https://www.placehold.it/500x500/EFEFEF/AAAAAA"/>
                                    @endif
                                </div>

                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                <div>
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new"> Select image </span>
                                        <span class="fileinput-exists"> Change </span>
                                        <input type="file" accept="image/png" name="logo_brand" class="file"> </span>
                                    <a href="javascript:;" id="removeLogo" class="btn red default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Name
                            <span class="required" aria-required="true"> *
                            </span>
                            <i class="fa fa-question-circle tooltips" data-original-title="Nama Brand" data-container="body"></i>
                        </label>
                        <div class="col-md-7">
                            <div class="input-icon right">
                                <input type="hidden" name="id" value="{{ $detail['id'] }}">
                                <input type="text" placeholder="Brand Name" class="form-control" name="name" @if (isset($detail['name'])) value="{{ $detail['name'] }}" @else value="{{ old('name') }}" @endif required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Visibility
                            <i class="fa fa-question-circle tooltips" data-original-title="Status brand. Visible/Hidden" data-container="body"></i>
                        </label>
                        <div class="col-md-7">
                            <div class="input-icon right">
                                <input type="checkbox" class="make-switch" data-size="small" data-on-color="info" data-on-text="Visible" data-off-color="default" data-off-text="Hidden" name="visibility" value="1" @if(old('visibility',$detail['visibility']??'')) checked @endif>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn yellow"><i class="fa fa-save"></i> Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue sbold uppercase">Vehicle Type List</span>
            </div>
        </div>
        <div class="portlet-body">
            <a href="#modalAddType" class="btn btn-success btn_add_type" data-toggle="modal" style="margin-bottom: 15px;">
                <i class="fa fa-plus"></i> Add New Type
            </a>
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                <thead>
                    <tr style="text-align: center">
                        <th style="text-align: center"> Name </th>
                        <th style="text-align: center"> AC Charging Max (kW) </th>
                        <th style="text-align: center"> DC Charging Max (kW) </th>
                        <th style="text-align: center"> Visibility </th>
                        <th style="text-align: center" width="100px;"> Action </th>
                    </tr>
                </thead>
                <tbody id="type">
                    @if (!empty($detail['type']))
                        @foreach ($detail['type'] as $type)
                            <tr style="text-align: center">
                                <td>{{ $type['name'] }}</td>
                                <td>{{ $type['ac_max'] == 0 ? "-" : $type['ac_max'] }}</td>
                                <td>{{ $type['dc_max'] == 0 ? "-" : $type['dc_max'] }}</td>
                                <td><input type="checkbox" class="make-switch type_visibility" data-size="small" data-on-color="info" data-on-text="Visible" data-off-color="default" data-id="{{$type['id']}}" data-off-text="Hidden" value="1" @if($type['visibility']??'') checked @endif></td>
                                <td style="width: 90px;">
                                    <a href="#modalUpdateType" class="btn btn-sm blue btn-primary" data-toggle="modal" data-id="{{ $type['id'] }}" data-name="{{ $type['name'] }}" data-ac="{{ $type['ac_max'] }}" data-dc="{{ $type['dc_max'] }}"><i class="fa fa-edit"></i></a>
                                    <a class="btn btn-sm red sweetalert-delete btn-primary" data-id="{{ $type['id'] }}" data-name="{{ $type['name'] }}"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr  style="text-align: center"><td colspan="3">No Data</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue sbold uppercase">User Owned</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="col-md-12" style="padding-bottom:20px; margin-bottom:20px; border-bottom: 1px solid #eef1f5;">
                <div class="form-group">
                    <div class="input-icon right">
                        <label class="col-md-2 control-label" style="text-align: right">
                            Filter By:
                        </label>
                    </div>
                    @php
                        if (request()->has('user')) {
                            $selected_type = 'user';
                            $value = request()->query('user');
                        } elseif (request()->has('status')) {
                            $selected_type = 'status';
                            $value = request()->query('status');
                        } elseif (request()->has('type')) {
                            $selected_type = 'type';
                            $value = request()->query('type');
                        } else {
                            $selected_type = 'user';
                            $value = "";
                        }
                    @endphp
                    <div class="col-md-3">
                        <select id="filter-type" class="form-control">
                            <option value="user" {{ $selected_type == 'user' ? 'selected' : '' }}>User</option>
                            <option value="status" {{ $selected_type == 'status' ? 'selected' : '' }}>Status</option>
                            <option value="type" {{ $selected_type == 'type' ? 'selected' : '' }}>Vehicle Type</option>
                        </select>
                    </div>
                    <div class="input-icon right">
                        <label class="col-md-1 control-label">
                            Value:
                        </label>
                    </div>
                    <div class="col-md-4" id="filter-value">
                        <select id="type" class="form-control">
                            @if (!empty($detail['type']))
                                @foreach ($detail['type'] as $type)
                                    <option value="{{ $type['id'] }}" {{ $value == $type['id'] ? 'selected' : '' }}>{{ $type['name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                        <input type="text" class="form-control" id="search" value="{{ $value }}" placeholder="Search">
                        <select id="status" class="form-control">
                            <option value="active" {{ $value == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="non-active" {{ $value == 'non-active' ? 'selected' : '' }}>Non-active</option>
                            <option value="deleted" {{ $value == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        </select>
                    </div>
                    <div class="col-md-1 text-center">
                        <button class="btn btn-info filter-button">Filter</button>
                    </div>
                    <div class="col-md-1 text-center">
                        <button class="btn btn-light filter-reset">Reset</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3" style="margin-bottom: 10px;">
                <label>
                    <select id="per_page" class="form-control input-sm input-xsmall input-inline">
                        <option value="5" {{ $pagination['per_page'] == 5 ? 'selected' : "" }}>5</option>
                        <option value="10" {{ $pagination['per_page'] == 10 ? 'selected' : "" }}>10</option>
                        <option value="15" {{ $pagination['per_page'] == 15 ? 'selected' : "" }}>15</option>
                        <option value="20" {{ $pagination['per_page'] == 20 ? 'selected' : "" }}>20</option>
                        <option value="50" {{ $pagination['per_page'] == 50 ? 'selected' : "" }}>50</option>
                    </select>
                     entries
                </label>
            </div>
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                <thead>
                    <tr style="text-align: center">
                        <th style="text-align: center"> Name </th>
                        <th style="text-align: center"> Email </th>
                        <th style="text-align: center"> User Status </th>
                        <th style="text-align: center"> Vehicle Type </th>
                    </tr>
                </thead>
                <tbody id="type">
                    @if (!empty($user_vehicle))
                        @foreach ($user_vehicle as $user)
                            <tr style="text-align: center">
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td>
                                    @if ($user['status'] == 'deleted')
                                        <span class="badge badge-danger">Deleted</span>
                                    @elseif ($user['status'] == 'non-active')
                                        <span class="badge badge-warning">Non-active</span>
                                    @elseif ($user['status'] == 'active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-default">Unknown</span>
                                    @endif
                                </td>
                                <td>{{ $user['vehicle_type'] }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr  style="text-align: center"><td colspan="4">No Data</td></tr>
                    @endif
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-5 col-sm-5">
                    <div class="dataTables_info" id="table_data_info" role="status" aria-live="polite">
                        Showing {{ (($pagination['current_page']-1)*$pagination['per_page'])+1 }} to {{ $pagination['current_page'] == $pagination['last_page'] ? $pagination['total_data'] : (($pagination['current_page']-1)*$pagination['per_page'])+$pagination['per_page'] }} of {{ $pagination['total_data'] }} entries
                    </div>
                </div>
                <div class="col-md-7 col-sm-7 text-right">
                    <div class="dataTables_paginate paging_bootstrap_number" id="table_data_paginate">
                        <ul class="pagination" style="visibility: visible;">
                            @php
                                $numPages = $pagination['last_page'];
                                $currentPage = $pagination['current_page'];
                                $firstPage = max(min($numPages - 4, $currentPage - 1), 1);
                                $lastPage = min(max(5, $currentPage + 1), $numPages);
                                $filter = "";

                                if (request()->has('user')) {
                                    $filter = "&user=".request()->query('user');
                                } elseif (request()->has('status')) {
                                    $filter = "&status=".request()->query('status');
                                } elseif (request()->has('type')) {
                                    $filter = "&type=".request()->query('type');
                                }
                            @endphp

                            @if ($firstPage > 1)
                                <li>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(['per_page' => request()->input('per_page', 10)], ['page' => 1])) }}{{ $filter }}" alt="First Page"><i class="fa fa-angle-double-left"></i></a>
                                </li>
                                @if ($firstPage > 2)
                                    <li class="disabled"><span>...</span></li>
                                @endif
                            @endif

                            @for ($i = $firstPage; $i <= $lastPage; $i++)
                                <li class="{{ $i == $currentPage ? 'active' : '' }}">
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(['per_page' => request()->input('per_page', 10)], ['page' => $i])) }}{{ $filter }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if ($lastPage < $numPages)
                                @if ($lastPage < $numPages - 1)
                                    <li class="disabled"><span>...</span></li>
                                @endif
                                <li>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(['per_page' => request()->input('per_page', 10)], ['page' => $numPages])) }}{{ $filter }}" alt="Last Page"><i class="fa fa-angle-double-right"></i></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAddType" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add Type</h4>
                </div>
                <div class="modal-body form">
                    <div class="form-body">
                    <form role="form" id="add-vehicle-type" action="{{ url('browse/vehicle/type/add') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name<span class="required" aria-required="true">*</span>
                                    <i class="fa fa-question-circle tooltips" data-original-title="Nama Type" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="hidden" name="id" value="{{ $detail['id'] }}">
                                        <input type="text" placeholder="Type Name" class="form-control" name="type_name" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">AC Max (kW)
                                    <i class="fa fa-question-circle tooltips" data-original-title="AC Charging Max in kW" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="number" placeholder="Max kW" class="form-control" name="ac_max" step=".01">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">DC Max (kW)
                                    <i class="fa fa-question-circle tooltips" data-original-title="DC Charging Max in kW" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="number" placeholder="Max kW" class="form-control" name="dc_max" step=".01">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Visibility
                                    <i class="fa fa-question-circle tooltips" data-original-title="Status Type. Visible/Hidden" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="checkbox" class="make-switch" data-size="small" data-on-color="info" data-on-text="Visible" data-off-color="default" data-off-text="Hidden" name="type_visibility" value="1" checked>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <center>
                                <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            </center>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUpdateType" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Update Type</h4>
                </div>
                <div class="modal-body form">
                    <div class="form-body">
                    <form role="form" id="update-vehicle-type" action="" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name<span class="required" aria-required="true">*</span>
                                    <i class="fa fa-question-circle tooltips" data-original-title="Nama Type" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="hidden" name="id" id="type_id_update" value="">
                                        <input type="text" id="type_name_update" placeholder="Type Name" class="form-control" name="name" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">AC Max (kW)
                                    <i class="fa fa-question-circle tooltips" data-original-title="AC Charging Max in kW" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="number" id="type_ac_max_update" placeholder="Max kW" class="form-control" name="ac_max" step=".01">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">DC Max (kW)
                                    <i class="fa fa-question-circle tooltips" data-original-title="DC Charging Max in kW" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="number" id="type_dc_max_update" placeholder="Max kW" class="form-control" name="dc_max" step=".01">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <center>
                                <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            </center>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
