@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/datemultiselect/jquery-ui.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
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
                <span class="caption-subject font-blue bold uppercase">Profile Setting</span>
            </div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                <div class="portlet-body">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_log" data-toggle="tab" aria-expanded="true"> Overview </a>
                        </li>
                        <li class="">
                            <a href="#tab_detail" data-toggle="tab" aria-expanded="false"> Update Detail </a>
                        </li>
                        <li class="">
                            <a href="#tab_password" data-toggle="tab" aria-expanded="false"> Update Password </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab_log">
                            <div class="portlet light" style="margin-bottom: 0px;">
                                <div class="row">
                                    <div class="col-md-12 profile-info">
                                        <h1 class="font-blue sbold uppercase">{{$detail['name']}}</h1>
                                        <div class="portlet sale-summary">
                                            <div class="portlet-title">
                                                <div class="caption font-blue sbold">
                                                    @if (isset($detail['role']))
                                                        @if ($detail['role'] == 'super_admin')
                                                            <span class="sale-num font-red sbold">Super Admin</span>
                                                        @else
                                                            <span class="sale-num font-blue sbold">Admin</span>
                                                        @endif
                                                    @else
                                                        <span class="sale-num font-blue sbold">Admin</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <span class="caption-subject font-blue sbold uppercase">Log Activity</span>
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
                                    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                                        <thead>
                                            <tr style="text-align: center">
                                                <th style="text-align: center;"> Action </th>
                                                {{-- <th style="width:30%; text-align: center;"> Path </th> --}}
                                                <th style="width:20%; text-align: center;"> IP Adress </th>
                                                <th style="width:30%; text-align: center;"> Date Time </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($user_log))
                                                @foreach ($user_log as $log)
                                                    <tr style="text-align: center">
                                                        <td>{{ $log['action'] }}</td>
                                                        {{-- <td>{{ $log['path'] }}</td> --}}
                                                        <td>{{ $log['ip_address'] }}</td>
                                                        <td>{{ date_format(date_create($log['created_at']),"d F Y H:i:s"); }}</td>
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
                        </div>
                        <div class="tab-pane fade in" id="tab_detail">
                            <form class="form-horizontal" role="form" action="{{ url()->current() }}" method="post" enctype="multipart/form-data">
                                <div class="portlet light">
                                    <div class="portlet-body form">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Name
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="name" value="@if(isset($detail['name'])){{ $detail['name'] }}@endif" placeholder="Name" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Email
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="email" class="form-control" name="email" value="@if(isset($detail['email'])){{ $detail['email'] }}@endif" placeholder="Email" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <input type="hidden" name="id" value="{{ $detail['id'] }}">
                                            <button type="submit" class="btn yellow"><i class="fa fa-save"></i> Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade in" id="tab_password">
                            <form class="form-horizontal" role="form" action="{{ url()->current() }}" method="post" enctype="multipart/form-data">
                                <div class="portlet light">
                                    <div class="portlet-body form">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Old Password
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="password" class="form-control" name="old_password" value="" placeholder="Old Password" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        New Password
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="password" class="form-control" name="new_password" value="" placeholder="New Password" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        New Password Confirmation
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="password" class="form-control" name="new_password_confirmation" value="" placeholder="Re-type New Password" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <input type="hidden" name="id" value="{{ $detail['id'] }}">
                                            <button type="submit" class="btn yellow"><i class="fa fa-save"></i> Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
