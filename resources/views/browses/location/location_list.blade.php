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
            var manual=1;
            $('#brand').on('switchChange.bootstrapSwitch','.status',function(){
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
                    url : "{{ url('browse/location/update/status') }}"+"/"+id,
                    data:{
                        _token:token,
                        id:switcher.data('id'),
                        status:newState
                    },
                    success:function(result){
                        if(result.status == 'success'){
                            toastr.info("Success update vehicle brand visibility");
                        }else{
                            manual=0;
                            toastr.warning("Fail update vehicle brand visibility");
                            switcher.bootstrapSwitch('state',!newState);
                        }
                    }
                })
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
                                        url : "{{ url('browse/location/developer/delete') }}"+'/'+id,
                                        success : function(result) {
                                            if (result.status == "success") {
                                                swal({
                                                    title: 'Deleted!',
                                                    text: 'Developer has been deleted.',
                                                    type: 'success',
                                                    showCancelButton: false,
                                                    showConfirmButton: false
                                                })
                                                SweetAlert.init()
                                                window.location.reload(true);
                                            } else if(result.status == "fail"){
                                                swal("Error!", result.messages[0], "error")
                                            } else {
                                                swal("Error!", "Something went wrong. Failed to delete developer.", "error")
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
    <script>
        $(document).ready(function() {
            $('.must-upper').on('input', function() {
                $(this).val($(this).val().toUpperCase()); // Convert input value to uppercase using jQuery
            });

            $('#new_developer_name').on('input', function() {
                value = $(this).val() // Convert input value to uppercase using jQuery
                if (value.length < 4) {
                    $('#new_developer_code').val(value);
                    $('#new_developer_code').trigger('input');
                }
            });

            $('#per_page').on('change', function () {
                let perPageValue = $(this).val();
                let currentUrl = window.location.href;
                let url = new URL(currentUrl);
                url.searchParams.set('per_page', perPageValue);
                window.location.href = url.toString();
            });

            $('#dev_per_page').on('change', function () {
                let perPageValue = $(this).val();
                let currentUrl = window.location.href;
                let url = new URL(currentUrl);
                url.searchParams.set('dev_per_page', perPageValue);
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
                <span class="caption-subject font-blue sbold uppercase">Location List</span>
            </div>
        </div>
        <div class="portlet-body">
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
            <div class="col-md-9" style="text-align: right;">
                <a href="{{ url('browse/location/add') }}" class="btn btn-success btn_add_location" style="margin-bottom: 15px;">
                    <i class="fa fa-plus"></i> Add New Location
                </a>
            </div>
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                <thead>
                    <tr>
                        <th> Location Name </th>
                        <th> Description </th>
                        <th> Operation Hour </th>
                        <th width="100px;"> Status </th>
                        <th> Action </th>
                    </tr>
                </thead>
                <tbody id="brand">
                    @if (!empty($locations))
                        @foreach ($locations as $location)
                            <tr>
                                <td>{{ $location['name'] }}</td>
                                <td>{{ $location['description'] }}</td>
                                <td>{{ $location['time_start'] }} - {{ $location['time_end'] }}</td>
                                <td><input type="checkbox" class="make-switch status" data-size="small" data-on-color="info" data-on-text="Active" data-off-color="default" data-id="{{$location['id']}}" data-off-text="Nonactive" value="1" @if($location['status']??'') checked @endif></td>
                                <td style="text-align: center"  style="width: 90px;">
                                    <a href="{{ url('browse/location') }}/{{ $location['id'] }}" class="btn btn-sm blue"><i class="fa fa-search"></i></a>
                                </td>
                            </tr>
                        @endforeach

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
                            @endphp

                            @if ($firstPage > 1)
                                <li>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(['per_page' => request()->input('per_page', 10)], ['page' => 1], ['dev_per_page' => request()->input('dev_per_page', 10)], ['dev_page' => request()->input('dev_page', 1)])) }}" alt="First Page"><i class="fa fa-angle-double-left"></i></a>
                                </li>
                                @if ($firstPage > 2)
                                    <li class="disabled"><span>...</span></li>
                                @endif
                            @endif

                            @for ($i = $firstPage; $i <= $lastPage; $i++)
                                <li class="{{ $i == $currentPage ? 'active' : '' }}">
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(['per_page' => request()->input('per_page', 10)], ['page' => $i], ['dev_per_page' => request()->input('dev_per_page', 10)], ['dev_page' => request()->input('dev_page', 1)])) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if ($lastPage < $numPages)
                                @if ($lastPage < $numPages - 1)
                                    <li class="disabled"><span>...</span></li>
                                @endif
                                <li>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(['per_page' => request()->input('per_page', 10)], ['page' => $numPages], ['dev_per_page' => request()->input('dev_per_page', 10)], ['dev_page' => request()->input('dev_page', 1)])) }}" alt="Last Page"><i class="fa fa-angle-double-right"></i></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue sbold uppercase">Developer List</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="col-md-3" style="margin-bottom: 10px;">
                <label>
                    <select id="dev_per_page" class="form-control input-sm input-xsmall input-inline">
                        <option value="5" {{ $developer_pagination['per_page'] == 5 ? 'selected' : "" }}>5</option>
                        <option value="10" {{ $developer_pagination['per_page'] == 10 ? 'selected' : "" }}>10</option>
                        <option value="15" {{ $developer_pagination['per_page'] == 15 ? 'selected' : "" }}>15</option>
                        <option value="20" {{ $developer_pagination['per_page'] == 20 ? 'selected' : "" }}>20</option>
                        <option value="50" {{ $developer_pagination['per_page'] == 50 ? 'selected' : "" }}>50</option>
                    </select>
                     entries
                </label>
            </div>
            <div class="col-md-9" style="text-align: right;">
                <a href="#modalAddDeveloper" class="btn btn-success btn_add_type" data-toggle="modal" style="margin-bottom: 15px;">
                    <i class="fa fa-plus"></i> Add New Developer
                </a>
            </div>
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="developer_table">
                <thead>
                    <tr>
                        <th> Developer Name </th>
                        <th> Developer Code </th>
                        <th> Action </th>
                    </tr>
                </thead>
                <tbody id="developer_data">
                    @if (!empty($developers))
                        @foreach ($developers as $developer)
                            <tr>
                                <td>{{ $developer['name'] }}</td>
                                <td>{{ $developer['developer_code'] }}</td>
                                <td style="text-align: center" style="width: 90px;">
                                    <a class="btn btn-sm red sweetalert-delete btn-primary" data-id="{{ $developer['id'] }}" data-name="{{ $developer['name'] }}"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-5 col-sm-5">
                    <div class="dataTables_info" id="table_data_info" role="status" aria-live="polite">
                        Showing {{ (($developer_pagination['current_page']-1)*$developer_pagination['per_page'])+1 }} to {{ $developer_pagination['current_page'] == $developer_pagination['last_page'] ? $developer_pagination['total_data'] : (($developer_pagination['current_page']-1)*$developer_pagination['per_page'])+$developer_pagination['per_page'] }} of {{ $developer_pagination['total_data'] }} entries
                    </div>
                </div>
                <div class="col-md-7 col-sm-7 text-right">
                    <div class="dataTables_paginate paging_bootstrap_number" id="table_data_paginate">
                        <ul class="pagination" style="visibility: visible;">
                            @php
                                $dev_numPages = $developer_pagination['last_page'];
                                $dev_currentPage = $developer_pagination['current_page'];
                                $dev_firstPage = max(min($dev_numPages - 4, $dev_currentPage - 1), 1);
                                $dev_lastPage = min(max(5, $dev_currentPage + 1), $dev_numPages);
                            @endphp

                            @if ($dev_firstPage > 1)
                                <li>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(['per_page' => request()->input('per_page', 10)], ['page' => request()->input('page', 1)], ['dev_per_page' => request()->input('dev_per_page', 10)], ['dev_page' => 1])) }}" alt="First Page"><i class="fa fa-angle-double-left"></i></a>
                                </li>
                                @if ($dev_firstPage > 2)
                                    <li class="disabled"><span>...</span></li>
                                @endif
                            @endif

                            @for ($j = $dev_firstPage; $j <= $dev_lastPage; $j++)
                                <li class="{{ $j == $dev_currentPage ? 'active' : '' }}">
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(['per_page' => request()->input('per_page', 10)], ['page' => request()->input('page', 1)], ['dev_per_page' => request()->input('dev_per_page', 10)], ['dev_page' => $j])) }}">{{ $j }}</a>
                                </li>
                            @endfor

                            @if ($dev_lastPage < $dev_numPages)
                                @if ($dev_lastPage < $dev_numPages - 1)
                                    <li class="disabled"><span>...</span></li>
                                @endif
                                <li>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(['per_page' => request()->input('per_page', 10)], ['page' => request()->input('page', 1)], ['dev_per_page' => request()->input('dev_per_page', 10)], ['dev_page' => $dev_currentPage])) }}" alt="Last Page"><i class="fa fa-angle-double-right"></i></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddDeveloper" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add Developer</h4>
                </div>
                <div class="modal-body form">
                    <div class="form-body">
                    <form role="form" action="{{ url('browse/location/developer/add') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name<span class="required" aria-required="true">*</span>
                                    <i class="fa fa-question-circle tooltips" data-original-title="Nama Brand" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="text" id="new_developer_name" placeholder="Developer Name" class="form-control" name="name" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Code<span class="required" aria-required="true">*</span>
                                    <i class="fa fa-question-circle tooltips" data-original-title="Nama Brand" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="text" id="new_developer_code" placeholder="Developer Code" class="form-control must-upper" pattern=".{3}" maxlength="3" name="code" required>
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
