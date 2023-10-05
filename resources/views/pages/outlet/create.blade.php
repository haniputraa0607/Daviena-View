@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-toastr/toastr.min.css' }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css' }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/datemultiselect/jquery-ui.css' }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-sweetalert/sweetalert.css' }}"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/css/select2.min.css' }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/css/select2-bootstrap.min.css' }}" rel="stylesheet" type="text/css" />

@endsection

@section('page-script')
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCOHBNv3Td9_zb_7uW-AJDU6DHFYk-8e9Y&v=3.exp&signed_in=true&libraries=places">
    </script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js' }}"
        type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-toastr/toastr.min.js' }}"
        type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js' }}"
        type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/js/select2.min.js' }}" type="text/javascript"></script>

    <script>
        $('.selectpicker').selectpicker();


        var map;

        var lat = "-7.7972";
        var long = "110.3688";

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
    

        var province_code = 0;
        var city_code = 0;
        
    
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
                var allowedExtensions = /(\.png)$/i;
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
                    <a href="{{ url('custom-page') }}">{{ $sub_title }}</a>
                </li>
            @endif
        </ul>
    </div><br>

    @include('layouts.notifications')

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue sbold uppercase ">New Outlet</span>
            </div>
        </div>
        <div class="portlet-body m-form__group row">
            <form class="form-horizontal" role="form" action="{{ route('outlet.store') }}" method="post"
                enctype="multipart/form-data" id="myForm">
                <div class="col-md-12">
                    <div class="form-body">

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Name<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Nama Outlet"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name') }}" placeholder="Name (Required)" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Address<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Alamat Outlet"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <textarea class="form-control" id="address" name="address" placeholder="Input your address here..." rows="3"
                                            required>{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="province-selection">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Province<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Daerah"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <select name="province" id="province-input" class="form-control select2-input" required> </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="city-selection">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">city<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Daerah"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <select name="city" id="city-input" class="form-control select2-input" required> </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="district-selection">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">District<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Daerah"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <select name="district_code" id="district-input" class="form-control select2-input" required> </select>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                        <input id="pac-input" id="field_event_map" class="controls field_event"
                                            type="text" placeholder="Enter a location" style="padding:10px;width:50%"
                                            onkeydown="if (event.keyCode == 13) return false;" name="location_map">
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
                                            <input type="text" class="form-control" name="latitude" id="lat"
                                                readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="longitude" id="lng"
                                                readonly>
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
                                        <input type="text" class="form-control" name="google_maps_link" placeholder="Google Maps Link"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Outlet Email<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Email"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="email" placeholder="Email"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Outlet Phone<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Telpon Outlet"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="phone" placeholder="Phone" value="{{ old('phone') }}"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Outlet Code<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Kode Outlet"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="outlet_code" value="{{ old('outlet_code') }}"
                                            placeholder="Outlet Code" required>
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
                                            data-off-text="Nonactive" name="status" value="Active"
                                            @if (old('status') == 'Active') checked @endif>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="partner-selection" style="display:none">
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
                                        <select name="partner" id="partner-input" class="form-control" required>
                                            <option value="">Choose Partner</option>
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
                                        <select name="partner_equal_id" id="partner_equal_id" class="form-control" required></select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="activities-selection">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Activities<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Activities"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <select name="activities[]" class="selectpicker form-control" multiple
                                            data-live-search="true" required>
                                            <option value="product">Product</option>
                                            <option value="treatment">Treatment</option>
                                            <option value="consultation">Consultation</option>
                                            <option value="consultation">Prescription</option>
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
                                            <img id="blah_image" src="{{ @$detail['image'] ? $detail['image'] : asset('images/logo.svg') }}" style="width:200px;" onerror="imgError(this)" alt="..." loading="lazy">
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
                                <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
