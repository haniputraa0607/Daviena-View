@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/datemultiselect/jquery-ui.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var SweetAlert = function() {
                return {
                    init: function() {
                        $(".sweetalert-delete-role").each(function() {
                            var token  	        = "{{ csrf_token() }}";
                            let id     	        = $(this).data('id');
                            let role_name   = $(this).data('name-role');
                            var location_delete_url = "{{ url('browse/role/delete')}}";
                            $(this).click(function() {
                                swal({
                                        title: "Are you sure want to delete "+role_name+"?",
                                        text: "Your will not be able to recover this data!",
                                        type: "warning",
                                        showCancelButton: true,
                                        confirmButtonClass: "btn-danger",
                                        confirmButtonText: "Yes, delete it!",
                                        closeOnConfirm: false
                                    },
                                    function(){
                                        window.location.href = location_delete_url+'/'+id;
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
                <a href="{{ url('custom-page') }}">{{ $sub_title }}</a>
            </li>
            @endif
        </ul>
    </div><br>

    @include('layouts.notifications')

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue sbold uppercase ">Role Detail</span>
            </div>
        </div>
        <div class="portlet-body m-form__group row">
            <form class="form-horizontal" role="form" action="{{ url()->current() }}" method="post" enctype="multipart/form-data" id="myForm">
                <div class="col-md-12">
                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Status<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Role Status" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-12">
                                        <input width="100px;" type="checkbox" class="make-switch" data-size="small" data-on-color="info" data-on-text="Active" data-off-color="default" data-off-text="Nonactive" name="status" value="1" @if($detail['status']??'') checked @endif>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Name<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Role Name" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="name" @if (isset($detail['name'])) value="{{ $detail['name'] }}" @else value="{{ old('name') }}" @endif placeholder="Role Name" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Permission<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Permission List" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        @php
                                            foreach ($feature_list as $module => $feature) {
                                                echo '<div class="row"><div class="col-md-12" style="padding-bottom:15px;"><label class="control-label caption-subject sbold uppercase">' . $module . '</label></div></div>';
                                                echo '<div class="form-group">';
                                                foreach ($feature as $value) {
                                                    $checked = '';
                                                    if (in_array($value['id'], $owned_features)) {
                                                        $checked = 'checked';
                                                    }
                                                    $input = '<div class="col-md-3 text-right" style="padding-bottom:15px;"><label class="control-label">' . $value['feature_type'] . '</label></div><div class="col-md-3" style="padding-bottom:15px;"><input width="100px;" type="checkbox" class="make-switch" data-size="small" data-on-color="info" data-on-text="On" data-off-color="default" data-off-text="Off" name="fature_ids[]" value="'. $value['id'] .'" '. $checked .'></div>';
                                                    echo $input;
                                                }
                                                echo "</div><hr>";
                                            }
                                        @endphp
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-offset-4 col-md-2 text-center">
                                <input type="hidden" name="id" value="{{ $detail['id'] }}">
                                <button type="submit" class="btn yellow"><i class="fa fa-save"></i> Update</button>
                            </div>
                            <div class="col-md-2 text-center">
                                <a class="btn red sweetalert-delete-role btn-primary" data-id="{{ $detail['id'] }}" data-name-role="{{ $detail['name'] }}"><i class="fa fa-trash-o"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
