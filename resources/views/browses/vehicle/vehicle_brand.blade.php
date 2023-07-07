@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .tooltip {
            z-index: 100000000;
        }
    </style>
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
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
            $('#brand').on('switchChange.bootstrapSwitch','.brand_visibility',function(){
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
                    url : "{{ url('browse/vehicle/update/visibility') }}"+"/"+id,
                    data:{
                        _token:token,
                        id:switcher.data('id'),
                        visibility:newState
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
                                        url : "{{ url('browse/vehicle/delete') }}"+'/'+id,
                                        success : function(result) {
                                            if (result.status == "success") {
                                                swal({
                                                    title: 'Deleted!',
                                                    text: 'Vehicle brand has been deleted.',
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
    <script>
        $(document).ready(function() {
            $('#per_page').on('change', function () {
                let perPageValue = $(this).val();
                let currentUrl = window.location.href;
                let url = new URL(currentUrl);
                url.searchParams.set('per_page', perPageValue);
                window.location.href = url.toString();
            });

            var searchQueryParam = new URLSearchParams(window.location.search).get('search');
            $('#filter-value input#search').val(searchQueryParam || '');

            // Filter button click event
            $('.filter-button').click(function() {
                var filterValue = $('#search').val();

                // Remove existing query parameters
                var url = new URL(window.location.href);
                url.searchParams.delete('search');

                // Add the new query parameter
                url.searchParams.set('search', filterValue);

                // Redirect to the new URL
                window.location.href = url.toString();
            });

            $('.filter-reset').click(function() {
                // Remove existing query parameters
                var url = new URL(window.location.href);
                url.searchParams.delete('search');

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

    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="glyphicon glyphicon-stats"></i>
                <span class="caption-subject bold">
                    Vehicle Summary
                </span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-lg-12 col-xs-12 col-sm-12">
                        <div class="col-md-6">
                            <div class="dashboard-stat grey">
                                <div class="visual">
                                    <i class="fa fa-car"></i>
                                </div>
                                <div class="details">
                                    <div class="number">
                                        <span data-counter="counterup" class="bold">{{ $count_brand }}</span> </div>
                                    <div class="desc">
                                        {{ $count_brand == 1 ? 'Brand' : 'Brands' }}
                                    </div>
                                </div>
                                <span class="more" href="">Total Vehicle Brand Registered</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="dashboard-stat grey">
                                <div class="visual">
                                    <i class="fa fa-cogs"></i>
                                </div>
                                <div class="details">
                                    <div class="number">
                                        <span data-counter="counterup" class="bold">{{ $count_type }}</span> </div>
                                    <div class="desc">
                                        {{ $count_type == 1 ? 'Type' : 'Types' }}
                                    </div>
                                </div>
                                <span class="more" href="">Total Vehicle Type Registered</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue sbold uppercase">Vehicle Brand List</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="col-md-12" style="padding-bottom:20px; margin-bottom:20px; border-bottom: 1px solid #eef1f5;">
                <div class="form-group">
                    @php
                        if (request()->has('search')) {
                            $value = request()->query('search');
                        } else {
                            $value = "";
                        }
                    @endphp
                    <div class="input-icon right">
                        <label class="col-md-offset-3 col-md-1 control-label">
                            Search:
                        </label>
                    </div>
                    <div class="col-md-4" id="filter-value">
                        <input type="text" class="form-control" id="search" value="{{ $value }}" placeholder="Search">
                    </div>
                    <div class="col-md-2 text-center">
                        <button class="btn btn-info filter-button" style="width: 100%;">Search</button>
                    </div>
                    <div class="col-md-2 text-center">
                        <button class="btn btn-light filter-reset" style="width: 100%;">Reset</button>
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
            <div class="col-md-9" style="text-align: right;">
                <a href="#modalAddBrand" class="btn btn-success btn_add_type" data-toggle="modal" style="margin-bottom: 15px;">
                    <i class="fa fa-plus"></i> Add New Brand
                </a>
            </div>
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                <thead>
                    <tr style="text-align: center">
                        <th> Name </th>
                        <th> Logo </th>
                        <th> Total Type </th>
                        <th> Visibility </th>
                        <th width="100px;"> Action </th>
                    </tr>
                </thead>
                <tbody id="brand">
                    @if (!empty($vehicle_brand))
                        @foreach ($vehicle_brand as $brand)
                            <tr style="text-align: center">
                                <td>{{ $brand['name'] }}</td>
                                <td>
                                    @if (!empty($brand['logo']))
                                        <span class="badge badge-success badge-sm">Available</span>
                                    @else
                                        <span class="badge badge-danger badge-sm">Un-Available</span>
                                    @endif
                                </td>
                                <td>{{ count($brand['type']) }}</td>
                                <td><input type="checkbox" class="make-switch brand_visibility" data-size="small" data-on-color="info" data-on-text="Visible" data-off-color="default" data-id="{{$brand['id']}}" data-off-text="Hidden" value="1" @if($brand['visibility']??'') checked @endif></td>
                                <td style="width: 90px;">
                                    <a class="btn btn-sm red sweetalert-delete btn-primary" data-id="{{ $brand['id'] }}" data-name="{{ $brand['name'] }}"><i class="fa fa-trash-o"></i></a>
                                    <a href="{{ url('browse/vehicle') }}/{{ $brand['id'] }}" class="btn btn-sm blue"><i class="fa fa-search"></i></a>
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
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(['per_page' => request()->input('per_page', 10)], ['page' => 1])) }}" alt="First Page"><i class="fa fa-angle-double-left"></i></a>
                                </li>
                                @if ($firstPage > 2)
                                    <li class="disabled"><span>...</span></li>
                                @endif
                            @endif

                            @for ($i = $firstPage; $i <= $lastPage; $i++)
                                <li class="{{ $i == $currentPage ? 'active' : '' }}">
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(['per_page' => request()->input('per_page', 10)], ['page' => $i])) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if ($lastPage < $numPages)
                                @if ($lastPage < $numPages - 1)
                                    <li class="disabled"><span>...</span></li>
                                @endif
                                <li>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(['per_page' => request()->input('per_page', 10)], ['page' => $numPages])) }}" alt="Last Page"><i class="fa fa-angle-double-right"></i></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAddBrand" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add Brand</h4>
                </div>
                <div class="modal-body form">
                    <div class="form-body">
                    <form role="form" action="{{ url('browse/vehicle/add') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">
                                    Logo<span class="required" aria-required="true">*</span> <i class="fa fa-question-circle tooltips" data-original-title="Gambar dengan ukuran square digunakan utnuk menjadi logo brand" data-container="body"></i>
                                    <br>
                                    <span class="required" aria-required="true"> (200*200)</span>
                                    <br>
                                    <span class="required" aria-required="true"> (Only PNG) </span>
                                </label>
                                <div class="col-md-7">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="max-width: 200px;">
                                            <img id="preview_logo_brand" src="https://www.placehold.it/200x200/EFEFEF/AAAAAA"/>
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px;"> </div>
                                        <div>
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new"> Select image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                <input type="file" accept="image/png" name="logo_brand" class="file" required>
                                            </span>
                                            <a href="javascript:;" id="removeLogo" class="btn red default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name<span class="required" aria-required="true">*</span>
                                    <i class="fa fa-question-circle tooltips" data-original-title="Nama Brand" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="text" placeholder="Brand Name" class="form-control" name="name" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Visibility<span class="required" aria-required="true">*</span>
                                    <i class="fa fa-question-circle tooltips" data-original-title="Brand Visibility" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="checkbox" class="make-switch" data-size="small" data-on-color="info" data-on-text="Visible" data-off-color="default" data-off-text="Hidden" name="visibility" value="1" checked>
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
