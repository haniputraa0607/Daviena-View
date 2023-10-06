@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-toastr/toastr.min.css' }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css' }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/datemultiselect/jquery-ui.css' }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-sweetalert/sweetalert.css' }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/css/select2.min.css' }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/css/select2-bootstrap.min.css' }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js' }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-toastr/toastr.min.js' }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js' }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/js/select2.min.js' }}" type="text/javascript"></script>
    <script type="text/javascript">
        Inputmask({
            "mask": "9999.9999.9999.9999"
        }).mask("#idc");
        Inputmask({ alias : "currency", prefix: 'Rp. ' }).mask("#consultation_price");
    </script>
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
            $('#field-commission-fee').addClass('hidden')
            $('#consultation_price').val('')
            $('#commission_fee').val('')
        }
    })
    $('#name').on('keyup',function () {
        generateUsername($('#admin-role-input').val());
    })

    var province_code = 0;
    var city_code = 0;
    
    $('#outlet-input').select2({
        placeholder: 'Select Outlet',
        theme: 'bootstrap',
        width: '100%',
        ajax: {
            url: `{{ route('api.outlet.name.id')}}`,
            headers: {
                "Authorization": "{{ session('access_token') }}"
            },
            dataType: 'json',
            delay: 250,
            processResults: function(result) {
                return {
                    results: $.map(result.result, function(item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });
    $('#province-input').select2({
        placeholder: 'Select Province',
        theme: 'bootstrap',
        width: '100%',
        ajax: {
            url: `{{ url('api/indonesia/provinces') }}`,
            headers: {
                "Authorization": "{{ session('access_token') }}"
            },
            dataType: 'json',
            delay: 250,
            processResults: function(result) {
                return {
                    results: $.map(result.data, function(item) {
                        return {
                            text: item.name,
                            id: item.code
                        }
                    })
                };
            },
            cache: true
        }
    });

    // $('#city-input').select2({
    //     placeholder: 'Select city',
    //     theme: 'bootstrap',
    //     width: '100%'
    // });

    // $('#district-input').select2({
    //     placeholder: 'Select district',
    //     theme: 'bootstrap',
    //     width: '100%'
    // });

    $('.select2-input').on('change', function(){
        if ($(this).attr('id') === 'province-input') {
            $('#city-input').empty().trigger('change');
            $('#district-input').empty().trigger('change');
        }

        if ($(this).attr('id') === 'city-input') {
            $('#district-input').empty().trigger('change');
        }

        province_code = $('#province-input').val();
        city_code = $('#city-input').val();
        
        cityUrl = `{{ url('api/indonesia/cities?province_code=') }}${province_code}`;
        districtUrl = `{{ url('api/indonesia/districts?city_code=') }}${city_code}`;

        $('#city-input').select2({
            placeholder: 'Select city',
            theme: 'bootstrap',
            width: '100%',
            ajax: {
                url: cityUrl,
                headers: {
                    "Authorization": "{{ session('access_token') }}"
                },
                dataType: 'json',
                delay: 250,
                processResults: function(result) {
                    return {
                        results: $.map(result.data, function(item) {
                            return {
                                text: item.name,
                                id: item.code
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $('#district-input').select2({
            placeholder: 'Select district',
            theme: 'bootstrap',
            width: '100%',
            ajax: {
                url: districtUrl,
                headers: {
                    "Authorization": "{{ session('access_token') }}"
                },
                dataType: 'json',
                delay: 250,
                processResults: function(result) {
                    return {
                        results: $.map(result.data, function(item) {
                            return {
                                text: item.name,
                                id: item.code
                            }
                        })
                    };
                },
                cache: true
            }
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
                                        <select name="type" id="admin-role-input" class="form-control" required>
                                            <option value="">--Select--</option>
                                            <option value="admin" @if (old('admin_role') == 'admin') selected @endif>Admin
                                            </option>
                                            <option value="salesman" @if (old('admin_role') == 'salesman') selected @endif
                                                @if (old('admin_role') == 'admin') selected @endif>
                                                Doctor</option>
                                            <option value="cashier" @if (old('admin_role') == 'cashier') selected @endif>
                                                Cashier</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Name (Required)" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Equal ID<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Equal ID"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="number" class="form-control" name="equal_id"
                                            placeholder="Equal ID (Required)" value="{{ old('equal_id') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">User Name<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="User Name"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="username" id='username'
                                            placeholder="User Name (Required & Unique)" value="{{ old('username') }}"
                                            readonly>
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
                                        <input type="email" class="form-control" name="email"
                                            placeholder="Email (Required & Unique)" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Phone<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips"
                                            data-original-title="Nomor telpon seluler" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="phone"
                                            placeholder="Phone Number(Required & Unique)"
                                            wire:value="{{ old('phone') }}" required>
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
                                        <input type="text" class="form-control" id="idc" name="idc"
                                            placeholder="NIK KTP (Required)" value="{{ old('idc') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="province-selection">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Province<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Province"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <select name="province" id="province-input" class="form-control select2-input"
                                            required data-type="provinces"></select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="city-selection">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">City<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="City"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <select name="city" id="city-input" class="form-control select2-input"
                                            required data-type="cities"></select>
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
                                        <select name="district_code" id="district-input" class="form-control select2-input"
                                            required data-type="districts"></select>
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
                                        <select name="outlet_id" id="outlet-input" class="form-control" required>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Birth Date<span class="required"
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

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Gender<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Gender"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gender"
                                                value="Male" @if (old('gender') == 'Male') checked @endif
                                                @if (empty(old('gender'))) checked @endif>
                                            <label class="form-check-label" for="gender">
                                                Male
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gender"
                                                value="Female" @if (old('gender') == 'Female') checked @endif>
                                            <label class="form-check-label" for="gender">
                                                Female
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        

                        <div class="form-group hidden" id="field-consultation-price">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Consultation Price<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Consultation Price"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="consultation_price" name="consultation_price"
                                            placeholder="Consultation Price (Required)" value="{{ old('consultation_price') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group hidden" id="field-commission-fee">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Commission Fee <i>(%)</i><span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Commission Fee"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="number" max="100" min="0" class="form-control" id="commission_fee" name="commission_fee"
                                            placeholder="Commission Fee (Required)" value="{{ old('commission_fee') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="level-role-selection">
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
                                        <select name="level" id="level-role-input" class="form-control" required>
                                            <option value="">--Select--</option>
                                            <option value="Admin" @if (old('level') == 'Admin') selected @endif>Admin
                                            </option>
                                            <option value="Super Admin" @if (old('level') == 'Super Admin') selected @endif>
                                                Super Admin</option>
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
                                    <label class="control-label">Status<span class="required"
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
                                            @if (old('is_active') == 'Active') checked @endif>
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
