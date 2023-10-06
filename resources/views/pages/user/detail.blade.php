@extends('layouts.main')
@php
    use App\Lib\MyHelper;
@endphp
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
        Inputmask({ alias : "currency", prefix: 'Rp. ' }).mask("#consultation_price");
    </script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/js/select2.min.js' }}" type="text/javascript"></script>
    <script type="text/javascript">
        var username, type
        function getFirstWord(sentence) {
            // Split the sentence into words using space as the delimiter
            const words = sentence.split(' ');
            
            // Get the first word (element at index 0)
            const firstWord = words[0];
            
            return firstWord;
        }

        function generateUsername(type) {
            $.ajax({
                url: 'http://timeless.test:8001/generate-username?type=' + type,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    
                    switch ($('#admin-role-input').val()) {
                        case 'salesman':
                            type = 'Dok'
                            break;
                    
                        case 'cashier':
                            type = 'Kas'
                            break;
                            
                        default:
                            type = 'Adm'
                            break;
                    }
                    username = type+getFirstWord($('#name').val())+response.result.next
                    $('#username').val(username)

                },
                error: function(xhr, status, error) {
                    console.error('Error');
                }
            });
        }

    
        $('#admin-role-input').change(function () {
            // Example usage:
            generateUsername($(this).val());

            if ($(this).val() === 'salesman'){
                $('#field-consultation-price').removeClass('hidden')
                $('#field-commission-fee').removeClass('hidden')
            } else{
                $('#field-consultation-price').addClass('hidden')
                $('#field-commission-fee').addClass('hidden')
                $('#consultation_price').val('')
                $('#commission_fee').val('')
            }
        })
        $('#name').on('keyup',function () {
            generateUsername($('#admin-role-input').val());
        })
        
        $(document).ready(function() {
            var province_code = {{ $detail['district']['city']['province_code'] }};
            var city_code = {{ $detail['district']['city_code'] }};
            var district_code = {{ $detail['district_code'] }};
            var provinceUrl = `{{ url('api/indonesia/provinces') }}`;
            var cityUrl = `{{ url('api/indonesia/cities?province_code=') }}`;
            var districtUrl = `{{ url('api/indonesia/districts?city_code=') }}`;

            var $provinceInput = $('#province-input');
            var $cityInput = $('#city-input');
            var $districtInput = $('#district-input');

            function populateSelectWithAjax(url, selectElement, selectedValue) {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: url,
                        method: "GET",
                        headers: {
                            "Authorization": "{{ session('access_token') }}"
                        },
                        dataType: 'json',
                        success: function(result) {
                            var $selectElement = $(selectElement);
                            $selectElement.empty();
                            $.each(result.data, function(index, item) {
                                $selectElement.append($('<option>', {
                                    value: item.code,
                                    text: item.name
                                }));
                            });
                            $selectElement.val(selectedValue).trigger('change');
                            $selectElement.select2({
                                placeholder: 'Select option',
                                theme: 'bootstrap',
                                width: '100%'
                            });
                            resolve();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching data:", error);
                            reject(error);
                        }
                    });
                });
            }

            function updateCityInput() {
                var selectedProvince = $provinceInput.val();
                populateSelectWithAjax(cityUrl + selectedProvince, "#city-input", city_code)
                    .then(function() {
                        $districtInput.empty().trigger('change'); // Reset district input
                    })
                    .catch(function(error) {
                        console.error("An error occurred:", error);
                    });
            }

            function updateDistrictInput() {
                var selectedCity = $cityInput.val();
                populateSelectWithAjax(districtUrl + selectedCity, "#district-input", district_code)
                    .catch(function(error) {
                        console.error("An error occurred:", error);
                    });
            }

            // Initial population of province, city, and district inputs
            populateSelectWithAjax(provinceUrl, "#province-input", province_code)
                .then(function() {
                    updateCityInput();
                })
                .catch(function(error) {
                    console.error("An error occurred:", error);
                });

            // Event listeners for input changes
            $provinceInput.on('change', function() {
                updateCityInput();
            });

            $cityInput.on('change', function() {
                updateDistrictInput();
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
                                                        Consultation Fee
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="control-label">{{ MyHelper::rupiah($detail['consultation_price'] ?? 0) }}</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Commission Fee
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="control-label">{{ ($detail['commission_fee'] ? ($detail['commission_fee']*100).'%' : '') ?? '0%' }}</label>
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
                                                        Admin Role
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-4">
                                                    <select name="type" id="admin-role-input" class="form-control"
                                                        required>
                                                        <option value="admin"
                                                            @if ($detail['type'] == 'admin') selected @endif>Admin</option>
                                                        <option value="salesman"
                                                            @if ($detail['type'] == 'salesman') selected @endif>Doctor
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
                                                        Name
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="name" id="name"
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
                                                    <input type="text" class="form-control" name="username" id="username"
                                                        value=" {{ $detail['username'] ?? '' }} " placeholder="Username"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="form-group @if ($detail['type'] != 'salesman') hidden @endif" id="field-consultation-price">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Consultation Price
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="consultation_price"  id="consultation_price"
                                                        value=" {{ $detail['consultation_price'] ?? '' }} " placeholder="Consultation Price">
                                                </div>
                                            </div>

                                            <div class="form-group hidden" id="field-commission-fee">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        Consultation Price
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="number" max="100" min="0" class="form-control" id="commission_fee" name="commission_fee"
                                                        placeholder="Commission Fee (Required)" value="{{ $detail['commission_fee'] ?? '' }}" required>
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
                                                    <input type="text" class="form-control" name="idc"
                                                        id="idc" value="{{ $detail['idc'] ?? '' }}"
                                                        placeholder="NIK KTP" required>
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

                                            <


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
                                                        Province
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <select name="province" id="province-input" class="form-control select2-input" required> </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-icon right">
                                                    <label class="col-md-4 control-label">
                                                        City
                                                        <span class="required" aria-required="true"> * </span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <select name="city" id="city-input" class="form-control select2-input" required> </select>
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
                                                        <select name="district_code" id="district-input" class="form-control select2-input" required> </select>
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
