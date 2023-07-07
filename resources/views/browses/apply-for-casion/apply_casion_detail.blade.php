@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/datemultiselect/jquery-ui.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var SweetAlert = function() {
            return {
                init: function() {
                    $(".button-action").each(function() {
                        var token  	= "{{ csrf_token() }}";
                        let action 	= $(this).data('action');
                        let id     	= $(this).data('id');
                        let name    = $(this).data('name');
                        let title
                        let text
                        let type
                        let confirmButtonClass
                        let confirmButtonText
                        if (action == 'accepted') {
                            title = "Are you sure want to accept "+name+"?"
                            type = "info"
                            confirmButtonClass = "btn-info"
                            confirmButtonText = "Yes, accept it!"
                        } else {
                            title = "Are you sure want to reject "+name+"?"
                            type = "warning"
                            confirmButtonClass = "btn-danger"
                            confirmButtonText = "Yes, reject it!"
                        }
                        $(this).click(function() {
                            swal({
                                    title: title,
                                    text: "Your will not be able to recover this data!",
                                    type: type,
                                    showCancelButton: true,
                                    confirmButtonClass: confirmButtonClass,
                                    confirmButtonText: confirmButtonText,
                                    closeOnConfirm: false
                                },
                                function(){
                                    $.ajax({
                                        type : "POST",
                                        url : "{{ url('browse/apply-casion/update/status') }}"+"/"+id,
                                        data:{
                                            _token:token,
                                            id:id,
                                            status:action
                                        },
                                        success : function(result) {
                                            if (result.status == "success") {
                                                swal({
                                                    title: 'Updated!',
                                                    text: 'Apply for Casion status updated',
                                                    type: 'success',
                                                    showCancelButton: false,
                                                    showConfirmButton: false
                                                })
                                                SweetAlert.init()
                                                window.location.reload(true);
                                            } else if(result.status == "fail"){
                                                swal("Error!", result.messages[0], "error")
                                            } else {
                                                swal("Error!", "Something went wrong. Failed to update status Apply for Casion.", "error")
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
                <span class="caption-subject font-blue sbold uppercase ">Apply for Casion Detail</span>
            </div>
        </div>
        <div class="portlet-body m-form__group row">
            <form class="form-horizontal" role="form" action="{{ url()->current() }}" method="post" enctype="multipart/form-data" id="myForm">
                <div class="col-md-12">
                    @php
                        if ($detail['status'] == 'rejected' || $detail['status'] == 'waiting') {
                            $disabled = "disabled";
                        } else {
                            $disabled = "";
                        }
                    @endphp
                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Status</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-12">
                                        <span class="btn btn-md btn-circle  @if ($detail['status'] == 'accepted') btn-info @elseif ($detail['status'] == 'rejected') btn-danger @else btn-warning @endif">{{ ucwords($detail['status']) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Submitted Time<span class="required" aria-required="true">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <span class="form-control" disabled>@if (isset($detail['time_submitted'])) {{ date('Y-m-d H:i:s', strtotime($detail['time_submitted'])) }} @endif</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Name<span class="required" aria-required="true">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="name" @if (isset($detail['name'])) value="{{ $detail['name'] }}" @else value="{{ old('name') }}" @endif {{ $disabled }} required >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Email<span class="required" aria-required="true">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="email" @if (isset($detail['email'])) value="{{ $detail['email'] }}" @else value="{{ old('email') }}" @endif {{ $disabled }} required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Phone Number<span class="required" aria-required="true">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="phone_number" @if (isset($detail['phone_number'])) value="{{ $detail['phone_number'] }}" @else value="{{ old('phone_number') }}" @endif {{ $disabled }} required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Location Name<span class="required" aria-required="true">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="location_name" @if (isset($detail['location_name'])) value="{{ $detail['location_name'] }}" @else value="{{ old('location_name') }}" @endif {{ $disabled }} required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Address<span class="required" aria-required="true">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <textarea name="location_address" class="form-control" rows="5" {{ $disabled }} required>@if(isset($detail['location_address'])){{ $detail['location_address'] }}@else{{ old('location_address') }}@endif</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Relation<span class="required" aria-required="true">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <select name="relation_to_location" class="form-control" {{ $disabled }}>
                                            <option value="owner" {{ $detail['relation_to_location'] == 'owner' ? "selected" : "" ; }}>Owner</option>
                                            <option value="management" {{ $detail['relation_to_location'] == 'management' ? "selected" : "" ; }}>Management</option>
                                            <option value="tenant" {{ $detail['relation_to_location'] == 'tenant' ? "selected" : "" ; }}>Tenant</option>
                                            <option value="customer" {{ $detail['relation_to_location'] == 'customer' ? "selected" : "" ; }}>Customer</option>
                                            <option value="other" {{ $detail['relation_to_location'] == 'other' ? "selected" : "" ; }}>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (in_array(10, session('granted_features')) || session('user_role') == 'super_admin')
                    <div class="form-actions">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <hr style="width:95%; margin-left:auto; margin-right:auto; margin-bottom:20px">
                            </div>
                            @if ($detail['status'] == 'accepted')
                                <div class="col-md-offset-5 col-md-2">
                                    <input type="hidden" name="id" value="{{ $detail['id'] }}">
                                    <button type="submit" class="btn yellow"><i class="fa fa-save"></i> Update</button>
                                </div>
                            @elseif ($detail['status'] == 'rejected')
                                <div class="col-md-offset-5 col-md-2">
                                    <a href="{{ url('browse/apply-casion') }}" class="btn btn-outline blue"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            @else
                                <div class="col-md-offset-5 col-md-2">
                                    <a class="btn btn-info button-action" data-id="{{ $detail['id'] }}" data-name="{{ $detail['location_name'] }}" data-action="accepted"><i class="fa fa-check"></i> Accept</a>
                                </div>
                                <div class="col-md-2">
                                    <a class="btn btn-danger button-action" data-id="{{ $detail['id'] }}" data-name="{{ $detail['location_name'] }}"  data-action="rejected"><i class="fa fa-times"></i> Reject</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
