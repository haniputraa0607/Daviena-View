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
    <script>
        $(document).ready(function() {
            $('#per_page').on('change', function () {
                let perPageValue = $(this).val();
                let currentUrl = window.location.href;
                let url = new URL(currentUrl);
                url.searchParams.set('per_page', perPageValue);
                window.location.href = url.toString();
            });

            $('#search_button').on('click', function () {
                let search = $('#search_ecs').val();
                if (search != "") {
                    let currentUrl = window.location.href;
                    let url = new URL(currentUrl);
                    url.searchParams.set('search', search);
                    url.searchParams.set('page', 1);
                    window.location.href = url.toString();
                } else {
                    let currentUrl = window.location.href;
                    let url = new URL(currentUrl);
                    url.searchParams.set('search', "");
                    window.location.href = url.toString();
                }
            });

            $(".price_group").each(function() {
                    var token  	    = "{{ csrf_token() }}";
                    let id     	    = $(this).data('id');
                    $(this).on('change', function () {
                        let price_group = $(this).val();
                        let url = "{{ url('browse/ecs') }}"+'/'+id+"/pricing/update";
                        $.ajax({
                            type : "POST",
                            url : url,
                            data:{
                                _token:token,
                                id:id,
                                custom_price:price_group,
                                type:'ajax'
                            },
                            success : function(result) {
                                if (result.status == "success") {
                                    toastr.info("Success update device pricing");
                                } else if(result.status == "fail"){
                                    toastr.warning(result.messages[0]);
                                } else {
                                    toastr.warning("Something went wrong. Failed to update device pricing.");
                                }
                            }
                        });
                    })
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
            <div class="col-md-6" style="margin-bottom: 10px;">
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
            <div class="col-md-6 col-sm-6" style="text-align:right;">
                <div id="table_data_filter" class="dataTables_filter">
                    <label>Search: <input type="search" class="form-control input-sm input-small input-inline" id="search_ecs" placeholder="" aria-controls="table_data" value="{{ app('request')->input('search') }}"></label>
                    <button class="btn btn-info btn-sm" id="search_button"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                <thead>
                    <tr>
                        <th> ECS Code </th>
                        <th> Location </th>
                        <th> Connector </th>
                        <th> Status </th>
                        <th> Pricing </th>
                    </tr>
                </thead>
                <tbody id="brand">
                    @if (!empty($devices))
                        @foreach ($devices as $device)
                            <tr style="text-align: center">
                                <td style="text-align: left">{{ $device['ecs_id'] }}</td>
                                <td style="text-align: left">{{ $device['location_name'] }}</td>
                                <td>{{ $device['num_of_connector'] }}</td>
                                <td>
                                    @if ($device['status'] == 'NEW')
                                        <span class="badge badge-success">New</span>
                                    @elseif ($device['status'] == 'ACTIVE')
                                        <span class="badge badge-primary">Active</span>
                                    @else
                                        <span class="badge badge-default">Inactive</span>
                                    @endif
                                </td>
                                <td style="width: 200px;">
                                        <select name="price_group" class="price_group form-control" data-id="{{ $device['id'] }}" {{ !in_array(29, session('granted_features')) && session('user_role') != 'super_admin' ? 'readonly disabled' : '' }}>
                                            <option value="0">Global</option>
                                            @foreach ($custom_price as $price)
                                                <option value="{{ $price['id'] }}" {{ $price['id'] == $device['pricing_group'] ? 'selected' : '' }}>{{ $price['name'] }}</option>
                                            @endforeach
                                        </select>
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

@endsection
