@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-toastr/toastr.min.css' }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css' }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/datemultiselect/jquery-ui.css' }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-sweetalert/sweetalert.css' }}"
        rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js' }}"
        type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-toastr/toastr.min.js' }}"
        type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js' }}"
        type="text/javascript"></script>
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
                <span class="caption-subject font-blue sbold uppercase ">New User</span>
            </div>
        </div>
        <div class="portlet-body m-form__group row">
            <form class="form-horizontal" role="form" action="{{ route('user.store') }}" method="post"
                enctype="multipart/form-data" id="myForm">
                <div class="col-md-12">
                    <div class="form-body">

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Name<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Nama"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="name" placeholder="Nama"
                                            value="{{ old('name') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">User Name<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Username"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="username" placeholder="User Name"
                                            value="{{ old('username') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Email<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Email"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="email" class="form-control" name="email" placeholder="Email"
                                            value="{{ old('email') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Phone<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Phone"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="phone" placeholder="Phone Number"
                                            value="{{ old('phone') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">IDC<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="NIK KTP"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="idc" placeholder="NIK KTP"
                                            value="{{ old('idc') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="district-selection">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">District<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="District"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <select name="district" id="district-input" class="form-control" required>
                                            <option value="">--Select--</option>
                                            @foreach ($districts as $district)
                                                <option value="{{ $district['code'] }}">{{ $district['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="outlet-selection">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Outlet<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Outlet"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <select name="outlet" id="outlet-input" class="form-control" required>
                                            <option value="">--Select--</option>
                                            @foreach ($outlets as $outlet)
                                                <option value="{{ $outlet['id'] }}">{{ $outlet['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">BirthDate<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Birthdate"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-10">
                                        <input type="date" class="form-control" name="birthdate"
                                            placeholder="Birthdate" value="{{ old('birthdate') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="admin-role-selection">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Admin Role<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips"
                                            data-original-title="Specific role for admin" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-10">
                                        <select name="admin_role" id="admin-role-input" class="form-control" required>
                                            <option value="">--Select--</option>
                                            <option value="admin">Admin</option>
                                            <option value="salesman">Salesman</option>
                                            <option value="cashier">Cashier</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="admin-role-selection">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Level<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Level"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-10">
                                        <select name="level" id="admin-role-input" class="form-control" required>
                                            <option value="">--Select--</option>
                                            <option value="Admin">Admin</option>
                                            <option value="Super Admin">Super Admin</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Password<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Password"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="password" class="form-control" name="password"
                                            placeholder="Password" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Password Confirm<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips"
                                            data-original-title="Password Confirmation" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="password" class="form-control" name="password_confirmation"
                                            placeholder="Password Confirmation" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Address<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Address"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" required>{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Active<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Active"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input width="100px;" type="checkbox" class="make-switch" data-size="small"
                                            data-on-color="info" data-on-text="Active" data-off-color="default"
                                            data-off-text="Nonactive" name="is_active" value="1"
                                            @if (old('is_active') ?? '') checked @endif>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="form-actions">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <hr style="width:95%; margin-left:auto; margin-right:auto; margin-bottom:20px">
                            </div>
                            <div class="col-md-offset-5 col-md-2 text-center">
                                <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
