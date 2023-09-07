@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/datemultiselect/jquery-ui.css' }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/datatables/datatables.min.css' }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-sweetalert/sweetalert.css' }}"
        rel="stylesheet" type="text/css" />
        <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/css/select2.min.css' }}" rel="stylesheet" type="text/css" />
        <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/css/select2-bootstrap.min.css' }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}"
        type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/scripts/datatable.js' }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/datatables/datatables.min.js' }}"
        type="text/javascript"></script>
    <script
        src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}"
        type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js' }}"
        type="text/javascript"></script>

    <script src="{{ asset('assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js') }}"
        type="text/javascript"></script>

    <script type="text/javascript">
        Inputmask({
            "mask": "9999.9999.9999.9999"
        }).mask("#idc");
    </script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/js/select2.min.js' }}" type="text/javascript"></script>
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
                <span class="caption-subject font-blue bold uppercase">User Setting</span>
            </div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                <div class="portlet-body">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_overview" data-toggle="tab" aria-expanded="true"> Overview </a>
                        </li>
                        <li class="">
                            <a href="#tab_detail" data-toggle="tab" aria-expanded="false"> Update Detail </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab_overview">
                            <form class="form-horizontal" role="form">
                                <div class="portlet light">
                                    <div class="portlet-body form">
                                        <div class="form-body">
                                            
                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Name
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="control-label">{{ $detail['name'] }}</label>
                                                </div>
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                               
                            </form>

                        </div>
                        <div class="tab-pane fade in" id="tab_detail">
                            <form class="form-horizontal" role="form"
                                action="{{ url('customer/update/') . '/' . $detail['id'] }}" method="post"
                                enctype="multipart/form-data">
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
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ $detail['name'] ?? '' }}" placeholder="Name" required>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-4 text-center">
                                            <input type="hidden" name="id" value="{{ $detail['id'] }}">
                                            {{-- <input type="password" class="form-control" width="30%"
                                                name="super_admin_password" placeholder="Enter Your current Password"
                                                required> --}}
                                            <button type="submit" class="btn yellow btn-block"><i
                                                    class="fa fa-save"></i> Update</button>
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
