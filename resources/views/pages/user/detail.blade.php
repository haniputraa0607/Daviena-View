@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/datemultiselect/jquery-ui.css' }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/datatables/datatables.min.css' }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-sweetalert/sweetalert.css' }}"
        rel="stylesheet" type="text/css" />
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
    <script>
        $(document).ready(function() {
            var table = $('#table_data').DataTable({
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
                lengthMenu: [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"]
                ],
                pageLength: 10
            });
        });
    </script>
    <script type="text/javascript">
        var SweetAlert = function() {
            return {
                init: function() {
                    $(".button-action").each(function() {
                        var token = "{{ csrf_token() }}";
                        let id = $(this).data('id');
                        let email = $(this).data('email');
                        let action = $(this).data('action');
                        let title
                        let text
                        let type
                        let confirmButtonClass
                        let confirmButtonText
                        if (action == 'admin') {
                            title = "Are you sure want to change role " + email + " to admin?"
                            type = "info"
                            confirmButtonClass = "btn-info"
                            confirmButtonText = "Yes, update it!"
                        } else {
                            title = "Are you sure want to change role " + email + " to super admin?"
                            type = "warning"
                            confirmButtonClass = "btn-danger"
                            confirmButtonText = "Yes, update it!"
                        }
                        $(this).click(function() {
                            let password = $(this).siblings('input[name="super_admin_password"]')
                                .val();
                            if (password === '') {
                                swal({
                                    title: "Warning",
                                    text: "Please Enter your password!",
                                    type: "warning"
                                });
                                return false;
                            } else {
                                swal({
                                        title: title,
                                        text: "Your will not be able to recover this data!",
                                        type: type,
                                        showCancelButton: true,
                                        confirmButtonClass: confirmButtonClass,
                                        confirmButtonText: confirmButtonText,
                                        closeOnConfirm: false
                                    },
                                    function() {
                                        $.ajax({
                                            type: "POST",
                                            url: "{{ url('browse/cms-user/update/role') }}" +
                                                "/" + id,
                                            data: {
                                                _token: token,
                                                id: id,
                                                role: action,
                                                super_admin_password: password
                                            },
                                            success: function(result) {
                                                if (result.status == "success") {
                                                    swal({
                                                        title: 'Updated!',
                                                        text: 'CMS user account role updated',
                                                        type: 'success',
                                                        showCancelButton: false,
                                                        showConfirmButton: false
                                                    })
                                                    SweetAlert.init()
                                                    window.location.reload(true);
                                                } else if (result.status ==
                                                    "fail") {
                                                    let errorMessages = "";
                                                    if (Array.isArray(result
                                                            .messages)) {
                                                        // handle messages as an array
                                                        errorMessages = result
                                                            .messages.join("\n");
                                                    } else {
                                                        // handle messages as an object
                                                        for (const [key,
                                                                value
                                                            ] of Object
                                                            .entries(result
                                                                .messages)) {
                                                            errorMessages += value[
                                                                0] + "\n";
                                                        }
                                                    }
                                                    swal("Error!", errorMessages,
                                                        "error")
                                                } else {
                                                    swal("Error!",
                                                        "Something went wrong. Failed to update CMS User role.",
                                                        "error")
                                                }
                                            }
                                        });
                                    });
                            }
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
                        <li class="">
                            <a href="#tab_password" data-toggle="tab" aria-expanded="false"> Update Password </a>
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
                                                        Status
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    @if ($detail['is_active'])
                                                        <span class="badge badge-success badge-lg">Active</span>
                                                    @else
                                                        <span class="badge badge-danger badge-lg">Non-Active</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Role
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="caption font-blue sbold">
                                                        @if (isset($detail['level']))
                                                            @if ($detail['level'] == 'Super Admin')
                                                                <label class="sale-num font-red sbold control-label">Super
                                                                    Admin</label>
                                                            @else
                                                                <label
                                                                    class="sale-num font-blue sbold control-label">Admin</label>
                                                            @endif
                                                        @else
                                                            <label
                                                                class="sale-num font-blue sbold control-label">Admin</label>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
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
                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Username
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="control-label">{{ $detail['username'] }}</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Email
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="control-label">{{ $detail['email'] }}</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Phone
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="control-label">{{ $detail['phone'] }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    {{-- @if ($detail['role'] == 'super_admin')
                                        <div class="col-md-offset-4 col-md-4 text-center">
                                            <input type="password" class="form-control super_admin_password" width="30%" name="super_admin_password" placeholder="Enter Your current Password">
                                            <a class="btn btn-info button-action btn-block" data-id="{{ $detail['id'] }}" data-email="{{ $detail['email'] }}" data-action="admin"><i class="fa fa-user"></i> Make Admin</a>
                                        </div>
                                    @else
                                        <div class="col-md-offset-4 col-md-4 text-center">
                                            <input type="password" class="form-control super_admin_password" width="30%" name="super_admin_password" placeholder="Enter Your current Password">
                                            <a class="btn btn-danger button-action btn-block" data-id="{{ $detail['id'] }}" data-email="{{ $detail['email'] }}"  data-action="super_admin"><i class="fa fa-user-secret"></i> Make Super Admin</a>
                                        </div>
                                    @endif --}}
                                </div>
                            </form>

                        </div>
                        <div class="tab-pane fade in" id="tab_detail">
                            <form class="form-horizontal" role="form"
                                action="{{ url('user/update/') . '/' . $detail['id'] }}" method="post"
                                enctype="multipart/form-data">
                                <div class="portlet light">
                                    <div class="portlet-body form">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Status
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input width="100px;" type="checkbox" class="make-switch"
                                                        data-size="small" data-on-color="info" data-on-text="Active"
                                                        data-off-color="default" data-off-text="Nonactive"
                                                        name="is_active" value="1"
                                                        @if ($detail['is_active'] ?? '') checked @endif>
                                                </div>
                                            </div>
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

                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Equal ID
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="number" class="form-control" name="equal_id"
                                                        value="{{ $detail['equal_id'] ?? '' }}" placeholder="equal_id"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        User Name
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="username"
                                                        value=" {{ $detail['username'] ?? '' }} " placeholder="Username"
                                                        required>
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
                                                    <input type="email" class="form-control" name="email"
                                                        value="{{ $detail['email'] ?? '' }}" placeholder="Email"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Phone
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="number" class="form-control" name="phone"
                                                        value="{{ $detail['phone'] ?? '' }}" placeholder="phone"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        IDC
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="number" class="form-control" name="idc"
                                                        value="{{ $detail['idc'] ?? '' }}" placeholder="NIK KTP"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Birth Date
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="date" class="form-control" name="birthdate"
                                                        value="{{ $detail['birthdate'] ?? '' }}" placeholder="Birthdate"
                                                        required>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Gender
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="gender"
                                                            id="gender" value="Male"
                                                            @if ($detail['gender'] == 'Male') checked @endif>
                                                        <label class="form-check-label" for="gender">
                                                            Male
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="gender"
                                                            id="gender" value="Female"
                                                            @if ($detail['gender'] == 'Female') checked @endif>
                                                        <label class="form-check-label" for="gender">
                                                            Female
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Admin Role
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-4">
                                                    <select name="admin_role" id="admin-role-input" class="form-control"
                                                        required>
                                                        <option value="admin"
                                                            @if ($detail['type'] == 'admin') selected @endif>Admin</option>
                                                        <option value="salesman"
                                                            @if ($detail['type'] == 'salesman') selected @endif>Salesman
                                                        </option>
                                                        <option value="cashier"
                                                            @if ($detail['type'] == 'cashier') selected @endif>Cashier
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Level
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-4">
                                                    <select name="level" id="admin-role-input" class="form-control"
                                                        required>
                                                        <option value="Admin"
                                                            @if ($detail['level'] == 'Admin') selected @endif>Admin
                                                        </option>
                                                        <option value="Super Admin"
                                                            @if ($detail['level'] == 'Super Admin') selected @endif>Super Admin
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        District
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <select name="district" id="district-input" class="form-control"
                                                        required>
                                                        <option value="">--Select--</option>
                                                        @foreach ($districts as $district)
                                                            <option value="{{ $district['code'] }}"
                                                                @if ($district['code'] == $detail['district_code']) selected @endif>
                                                                {{ $district['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Outlet
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <select name="outlet" id="outlet-input" class="form-control"
                                                        required>
                                                        <option value="">--Select--</option>
                                                        @foreach ($outlets as $outlet)
                                                            <option value="{{ $outlet['id'] }}"
                                                                @if ($outlet['id'] == $detail['outlet_id']) selected @endif>
                                                                {{ $outlet['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Address
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" required>{{ $detail['address'] }}</textarea>
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
                                            <input type="password" class="form-control" width="30%"
                                                name="super_admin_password" placeholder="Enter Your current Password"
                                                required>
                                            <button type="submit" class="btn yellow btn-block"><i
                                                    class="fa fa-save"></i> Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade in" id="tab_password">
                            <form class="form-horizontal" role="form"
                                action="{{ url('user/update') . '/' . $detail['id'] }}" method="post"
                                enctype="multipart/form-data">
                                <div class="portlet light">
                                    <div class="portlet-body form">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        New Password
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="password" class="form-control" name="new_password"
                                                        value="" placeholder="New Password" required>
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
                                                    <input type="password" class="form-control"
                                                        name="new_password_confirmation" value=""
                                                        placeholder="Re-type New Password" required>
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
                                            <input type="password" class="form-control" width="30%"
                                                name="super_admin_password" placeholder="Enter Your current Password"
                                                required>
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
