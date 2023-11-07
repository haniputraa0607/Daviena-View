@extends('layouts.main')


@php
    $coordinates = json_decode($detail['coordinates'], true);
    $latitude = $coordinates['latitude'];
    $longitude = $coordinates['longitude'] ?? $coordinates['langitude'];
@endphp

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/datemultiselect/jquery-ui.css' }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css' }}"
        rel="stylesheet" type="text/css" />

    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/datatables/datatables.min.css' }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-sweetalert/sweetalert.css' }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/css/select2.min.css' }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/css/select2-bootstrap.min.css' }}"
        rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}"
        type="text/javascript"></script>

    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js' }}"
        type="text/javascript"></script>

    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/scripts/datatable.js' }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/datatables/datatables.min.js' }}"
        type="text/javascript"></script>
    <script
        src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}"
        type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js' }}"
        type="text/javascript"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCOHBNv3Td9_zb_7uW-AJDU6DHFYk-8e9Y&v=3.exp&signed_in=true&libraries=places">
    </script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/js/select2.min.js' }}"
        type="text/javascript"></script>

    <script>
        $('.selectpicker').selectpicker();

        var map;

        var lat = {{ $latitude }};
        var long = {{ $longitude }};

        var markers = [];

        function initialize() {
            var haightAshbury = new google.maps.LatLng(lat, long);
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat, long),
                map: map,
                anchorPoint: new google.maps.Point(0, -29)
            });

            var mapOptions = {
                zoom: 12,
                center: haightAshbury,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            var infowindow = new google.maps.InfoWindow({
                content: '<p>Marker Location:</p>'
            });

            map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

            var input = /** @type  {HTMLInputElement} */ (
                document.getElementById('pac-input'));

            var types = document.getElementById('type-selector');
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.bindTo('bounds', map);

            var infowindow = new google.maps.InfoWindow();

            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                deleteMarkers();
                infowindow.close();
                marker.setVisible(true);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17); // Why 17? Because it looks good.
                }
                addMarker(place.geometry.location);
            });

            google.maps.event.addListener(map, 'click', function(event) {

                deleteMarkers();
                addMarker(event.latLng);
                // marker.openInfoWindowHtml(latLng);
                // infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                // infowindow.open(map, marker);
            });
            // Adds a marker at the center of the map.
            addMarker(haightAshbury);
        }

        function placeMarker(location) {
            marker = new google.maps.Marker({
                position: location,
                map: map,
            });

            markers.push(marker);

            infowindow = new google.maps.InfoWindow({
                content: 'Latitude: ' + location.lat() + '<br>Longitude: ' + location.lng()
            });
            infowindow.open(map, marker);
        }

        // Add a marker to the map and push to the array.

        function addMarker(location) {
            var marker = new google.maps.Marker({
                position: location,
                map: map
            });

            $('#lat').val(location.lat());
            $('#lng').val(location.lng());
            markers.push(marker);
        }

        // Sets the map on all markers in the array.

        function setAllMap(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }

        // Removes the markers from the map, but keeps them in the array.
        function clearMarkers() {
            setAllMap(null);
        }

        // Shows any markers currently in the array.
        function showMarkers() {
            setAllMap(map);
        }

        // Deletes all markers in the array by removing references to them.
        function deleteMarkers() {
            clearMarkers();
            markers = [];
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>

    <script type="text/javascript">
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

        $("#generate-button").click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{ route('api.outlet.generate-schedule', $detail['id']) }}",
                headers: {
                    "Authorization": "{{ session('access_token') }}"
                },
                success: function(response) {
                    if (response.status == "success") {
                        swal({
                            title: 'Generated!',
                            text: '{{ $detail['name'] }} schedule has been generated.',
                            type: 'success',
                            showCancelButton: false,
                            showConfirmButton: false,
                            delay: 500
                        })
                        setTimeout(() => {
                            window.location.reload(true);
                        }, 1500);
                    } else if (response.status == "fail") {
                        swal("Error!", response.messages[0], "error")
                    } else {
                        swal("Error!",
                            "Something went wrong. Failed to delete vehicle brand.",
                            "error")
                    }
                }
            });
        });
        $(".update-schedule").click(function(e) {
            e.preventDefault();
            var id = $(this).attr('id')
            var url = "{{ route('api.outlet-schedule.update') }}"
            var data = {
                id: $(`#${$(this).attr('id')}_id`).val(),
                open: $(`#${$(this).attr('id')}_open`).val(),
                close: $(`#${$(this).attr('id')}_close`).val(),
                is_closed: $(`#${$(this).attr('id')}_is_closed`).is(':checked') === true ? 0 : 1,
                all_products: $(`#${$(this).attr('id')}_all_products`).is(':checked') === true ? 1 : 0
            }

            $.ajax({
                type: "GET",
                url: url,
                headers: {
                    "Authorization": "{{ session('access_token') }}"
                },
                data: data,
                success: function(response) {
                    console.log(response);
                    if (response.status == "success") {
                        swal({
                            title: 'Schedule Updated!',
                            text: 'Schedule has been updated.',
                            type: 'success',
                            showCancelButton: false,
                            showConfirmButton: false,
                            timer: 2000,
                        })
                        // setTimeout(() => {
                        //     window.location.reload(true);
                        // }, 1500);
                    } else if (response.status == "fail") {
                        swal("Error!", response.messages[0], "error")
                    } else {
                        swal("Error!",
                            "Something went wrong. Failed to delete vehicle brand.",
                            "error")
                    }
                }
            });
        });
    </script>

    <script>
            
        $('#partner_equal_id').select2({
            placeholder: 'Select Partner',
            theme: 'bootstrap',
            width: '100%',
            ajax: {
                url: `{{ url('api/be/outlet/partner-equal-filter') }}`,
                headers: {
                    "Authorization": "{{ session('access_token') }}"
                },
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
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
        
        function readURL(input, level) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var fileimport = $('#' + input.id).val();
                var allowedExtensions = /(\.png|\.jpg|\.jpeg)$/i; 
                if (!allowedExtensions.exec(fileimport)) {
                alert('Gambar harus bertipe gambar');
                $('#' + input.id).val('');
                return false;
                }
                reader.onload = function(e) {
                $('#blah_' + level).attr('src', e.target.result).width(200);
                // .height();
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function imgError(data) {
            console.log('error_img');
            data.setAttribute('src', '{{ asset("images/logo.svg") }}');
        }
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
                <span class="caption-subject font-blue bold uppercase">Detail Clinic</span>
            </div>
        </div>

        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_overview" data-toggle="tab" aria-expanded="true"> Overview </a>
            </li>
            <li class="">
                <a href="#tab_detail" data-toggle="tab" aria-expanded="false"> Update Detail </a>
            </li>
            <li class="">
                <a href="#tab_password" data-toggle="tab" aria-expanded="false">Schedule </a>
            </li>
        </ul>
        <div class="tab-content">
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
                                            @if ($detail['status'])
                                                <span class="badge badge-success badge-lg">Active</span>
                                            @else
                                                <span class="badge badge-danger badge-lg">Non-Active</span>
                                            @endif
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
                                                Email
                                            </label>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="control-label">{{ $detail['outlet_email'] }}</label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="input-icon right">
                                            <label class="col-md-4 control-label">
                                                Phone
                                            </label>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="control-label">{{ $detail['outlet_phone'] }}</label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="input-icon right">
                                            <label class="col-md-4 control-label">
                                                Code
                                            </label>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="control-label">{{ $detail['outlet_code'] }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-icon right">
                                            <label class="col-md-4 control-label">
                                                Address
                                            </label>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="control-label">{{ $detail['address'] }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-icon right">
                                            <label class="col-md-4 control-label">
                                                Province
                                            </label>
                                        </div>
                                        <div class="col-md-8">
                                            <label
                                                class="control-label">{{ $detail['district']['city']['province']['name'] }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-icon right">
                                            <label class="col-md-4 control-label">
                                                City
                                            </label>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="control-label">{{ $detail['district']['city']['name'] }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-icon right">
                                            <label class="col-md-4 control-label">
                                                District
                                            </label>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="control-label">{{ $detail['district']['name'] }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-icon right">
                                            <label class="col-md-4 control-label">
                                                Google Maps Link
                                            </label>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="control-label">
                                                <a  target="_blank" href="{{ $detail['google_maps_link'] ?? ''}}">{{ $detail['google_maps_link'] ?? '-' }}</a>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-icon right">
                                            <label class="col-md-4 control-label">
                                                Partner
                                            </label>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="control-label">{{ $detail['partner_equal']['name'] ?? '-' }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-icon right">
                                            <label class="col-md-4 control-label">
                                                Activities
                                            </label>
                                        </div>
                                        <div class="col-md-8">
                                            @php
                                                $jsonString = '["treatment", "example", "test"]';
                                                $array = json_decode($detail['activities'], true);
                                                $capitalizedArray = array_map('ucfirst', $array);
                                            @endphp
                                            <label class="control-label">{{ implode(', ', $capitalizedArray) }}</label>
                                        </div>
                                    </div>
                                    

                                    <div class="form-group">
                                        <div class="input-icon right">
                                            <label class="col-md-4 control-label">
                                                Image
                                            </label>
                                        </div>
                                        <div class="col-md-8">
                                            <img id="blah_image" src="{{ @$detail['images'] ? env('API_URL').json_decode($detail['images']) : asset('images/logo.svg') }}" style="width:200px;" onerror="imgError(this)" alt="..." loading="lazy">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="tab-pane fade in" id="tab_detail">
                    <div class="portlet-body m-form__group row">
                        <form class="form-horizontal" role="form"
                            action="{{ url('outlet/update') . '/' . $detail['id'] }}" method="post"
                            enctype="multipart/form-data" id="myForm">
                            <div class="col-md-12">
                                <div class="form-body">

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label">Name<span class="required"
                                                        aria-required="true">*</span>
                                                    <i class="fa fa-question-circle tooltips" data-original-title="Nama"
                                                        data-container="body"></i>
                                                </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="name"
                                                        value="@if (isset($detail['name'])) {{ $detail['name'] }} @endif"
                                                        placeholder="Nama" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label">Address<span class="required"
                                                        aria-required="true">*</span>
                                                    <i class="fa fa-question-circle tooltips"
                                                        data-original-title="Address" data-container="body"></i>
                                                </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="address"
                                                        value="@if (isset($detail['address'])) {{ $detail['address'] }} @endif"
                                                        placeholder="Alamat" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <x-district-city-province />

                                    <div class="form-group featureLocation" style="margin-top: 30px;">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label">Coordinates<span class="required"
                                                        aria-required="true">*</span>
                                                    <i class="fa fa-question-circle tooltips"
                                                        data-original-title="Pin point lokasi agar mudah ditemukan melalui map"
                                                        data-container="body"></i>
                                                </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="col-md-10">
                                                    <input id="pac-input" id="field_event_map"
                                                        class="controls field_event" type="text"
                                                        placeholder="Enter a location" style="padding:10px;width:50%"
                                                        onkeydown="if (event.keyCode == 13) return false;"
                                                        name="location_map">
                                                    <div id="map-canvas" style="width:100%;height:380px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label for=""></label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="col-md-10">
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="latitude"
                                                            id="lat" readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="longitude"
                                                            id="lng" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label">Google Maps Link<span class="required"
                                                        aria-required="true">*</span>
                                                    <i class="fa fa-question-circle tooltips" data-original-title="Google Maps Link"
                                                        data-container="body"></i>
                                                </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="google_maps_link" value="@if (isset($detail['google_maps_link'])) {{ $detail['google_maps_link'] }} @endif" placeholder="Google Maps Link"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label">Clinic Email<span class="required"
                                                        aria-required="true">*</span>
                                                    <i class="fa fa-question-circle tooltips" data-original-title="Email"
                                                        data-container="body"></i>
                                                </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="col-md-10">
                                                    <input type="email" class="form-control" name="email"
                                                        value="@if (isset($detail['outlet_email'])) {{ $detail['outlet_email'] }} @endif"
                                                        placeholder="Email" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label">Clinic Phone<span class="required"
                                                        aria-required="true">*</span>
                                                    <i class="fa fa-question-circle tooltips" data-original-title="Phone"
                                                        data-container="body"></i>
                                                </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="phone"
                                                        value="@if (isset($detail['outlet_phone'])) {{ $detail['outlet_phone'] }} @endif"
                                                        placeholder="Phone" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label">Clinic Code<span class="required"
                                                        aria-required="true">*</span>
                                                    <i class="fa fa-question-circle tooltips"
                                                        data-original-title="Clinic Code" data-container="body"></i>
                                                </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="outlet_code"
                                                        value="@if (isset($detail['outlet_code'])) {{ $detail['outlet_code'] }} @endif"
                                                        placeholder="Clinic Code" required>
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
                                                    <input width="100px;" type="checkbox" class="make-switch"
                                                        data-size="small" data-on-color="info" data-on-text="Active"
                                                        data-off-color="default" data-off-text="Nonactive" name="status"
                                                        value="Active" @if ($detail['status'] == 'Active') checked @endif>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group" id="admin-role-selection" style="display:none;">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label">Partner<span class="required"
                                                        aria-required="true">*</span>
                                                    <i class="fa fa-question-circle tooltips"
                                                        data-original-title="Partner" data-container="body"></i>
                                                </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="col-md-10">
                                                    <select name="partner" id="partner-input" class="form-control"
                                                        required>
                                                        <option value="">--Select--</option>
                                                        <option value="1" selected>Partner 1</option>
                                                        <option value="2">Partner 2</option>
                                                        <option value="3">Partner 3</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group" id="partner-selection">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label">Partner<span class="required"
                                                        aria-required="true">*</span>
                                                    <i class="fa fa-question-circle tooltips" data-original-title="Partner"
                                                        data-container="body"></i>
                                                </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="col-md-10">
                                                    <select name="partner_equal_id" id="partner_equal_id" class="form-control" required>
                                                        @isset($detail['partner_equal'])
                                                            <option value="{{ $detail['partner_equal']['id'] }}" selected>{{ $detail['partner_equal']['name'] }}</option>
                                                        @endisset
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" id="activities-selection">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label">Activities<span class="required"
                                                        aria-required="true">*</span>
                                                    <i class="fa fa-question-circle tooltips"
                                                        data-original-title="Activities" data-container="body"></i>
                                                </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="col-md-10">
                                                    <select name="activities[]" class="selectpicker form-control" multiple
                                                        data-live-search="true" required>
                                                        <option value="product"
                                                            {{ in_array('product', json_decode($detail['activities'])) ? 'selected' : '' }}>
                                                            Product</option>
                                                        <option value="treatment"
                                                            {{ in_array('treatment', json_decode($detail['activities'])) ? 'selected' : '' }}>
                                                            Treatment</option>
                                                        <option value="consultation"
                                                            {{ in_array('consultation', json_decode($detail['activities'])) ? 'selected' : '' }}>
                                                            Consultation</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label">Image<span class="required" aria-required="true">*</span>
                                                    <i class="fa fa-question-circle tooltips" data-original-title="Image" data-container="body"></i>
                                                </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="col-md-10">
                                                    <div class="alert alert-success text-center col-sm-12">
                                                        <img id="blah_image" src="{{ @$detail['images'] ? env('API_URL').json_decode($detail['images']) : asset('images/logo.svg') }}" style="width:200px;" onerror="imgError(this)" alt="..." loading="lazy">
                                                    </div>
                                                    <input class="form-control" name="image" style="display:none;" id="image" type="file" onchange="readURL(this, 'image');">
                                                    <button class="btn btn-outline-success btn-sm" type="button" onclick="$('#image').click();">Upload</button>
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
                                            <button type="submit" class="btn yellow btn-block"><i
                                                    class="fa fa-check"></i>
                                                Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="tab-pane fade in" id="tab_password">
                    {{-- @dd($detail['outlet_schedule']) --}}

                    <table class="table trace trace-as-text table-striped table-bordered table-hover dt-responsive"
                        id="table_data">
                        <thead class="trace-head">
                            <tr>
                                <th>Day</th>
                                <th>Open Hour</th>
                                <th>Close Hour</th>
                                <th>Is Closed</th>
                                <th>All Products</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($detail['outlet_schedule'] as $item)
                                <tr>
                                    <td>
                                        <input id="{{ $item['id'] . '_id' }}" type="hidden" name="id[]"
                                            value="{{ $item['id'] }}">
                                        {{ $item['day'] }}
                                    </td>
                                    <td>
                                        @isset($item['open'])
                                            <input id="{{ $item['id'] . '_open' }}" type="time" class="form-control"
                                                name="open[]" placeholder="open (Required)" value="{{ $item['open'] }}"
                                                required>
                                        @else
                                            <input id="{{ $item['id'] . '_open' }}" type="time" class="form-control"
                                                name="open[]" placeholder="open (Required)" value="{{ old('open') }}"
                                                required>
                                        @endisset
                                    </td>
                                    <td>
                                        @isset($item['close'])
                                            <input id="{{ $item['id'] . '_close' }}" type="time" class="form-control"
                                                name="close[]" placeholder="close (Required)" value="{{ $item['close'] }}"
                                                required>
                                        @else
                                            <input id="{{ $item['id'] . '_close' }}" type="time" class="form-control"
                                                name="close[]" placeholder="close (Required)" value="{{ old('close') }}"
                                                required>
                                        @endisset
                                    </td>
                                    <td>
                                        <input id="{{ $item['id'] . '_is_closed' }}" width="50px;" type="checkbox"
                                            class="make-switch" data-on-color="info" data-on-text="Open"
                                            data-off-color="default" data-off-text="Closed" name="is_close"
                                            @if ($item['is_closed'] == 0) checked @endif>
                                    </td>
                                    <td>
                                        <input id="{{ $item['id'] . '_all_products' }}" width="50px;" type="checkbox"
                                            class="make-switch" data-on-color="info" data-on-text="True"
                                            data-off-color="default" data-off-text="False" name="all_products"
                                            @if ($item['all_products'] == true) checked @endif>
                                    </td>
                                    <td>
                                        <a id="{{ $item['id'] }}" class="update-schedule btn yellow ">
                                            <li class="fa fa-pencil" aria-hidden="true"></li>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <center>
                                            <p><span><strong class="text-primary">{{ $detail['name'] }}</strong> doesn't
                                                    have any schedule yet, click the button bellow to generate
                                                    schedule!</span></p>
                                        </center>
                                        <center>
                                            <a id='generate-button' class="btn blue"
                                                data-id="{{ $detail['id'] }}">Generate Schedule</a>
                                        </center>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



    </div>
@endsection
