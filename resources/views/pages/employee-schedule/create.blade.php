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
    </script>
   <script type="text/javascript">
   

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
                <span class="caption-subject font-blue sbold uppercase ">New Customer</span>
            </div>
        </div>
        <div class="portlet-body m-form__group row">
            <form class="form-horizontal" role="form" action="{{ route('customer.store') }}" method="post"
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
                                        <input type="text" class="form-control" name="name"
                                            placeholder="Name (Required)" value="{{ old('name') }}" required>
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
