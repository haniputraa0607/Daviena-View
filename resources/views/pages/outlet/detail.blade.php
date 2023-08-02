@extends('layouts.main')

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

    @php
        $coordinates = json_decode($detail['coordinates'], true);
        $latitude = $coordinates['latitude'];
        $longitude = $coordinates['longitude']; 
    @endphp
    <script>
        $('.selectpicker').selectpicker();

        var map;

        var lat = {{  $latitude }};
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
                <span class="caption-subject font-blue bold uppercase">Detail Outlet</span>
            </div>
        </div>

        <div class="portlet-body m-form__group row">
            <form class="form-horizontal" role="form" action="{{ url('outlet/update') . '/' . $detail['id'] }}"
                method="post" enctype="multipart/form-data" id="myForm">
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
                                            value="@if (isset($detail['name'])) {{ $detail['name'] }} @endif"
                                            placeholder="Nama" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Address<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Address"
                                            data-container="body"></i>
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
                                                <option value="{{ $district['code'] }}"
                                                    {{ $district['code'] == $detail['district_code'] ? 'selected' : '' }}>
                                                    {{ $district['name'] }}</option>
                                            @endforeach
                                        </select>
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
                                    <label class="control-label">Outlet Email<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Email"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="email"
                                            value="@if (isset($detail['outlet_email'])) {{ $detail['outlet_email'] }} @endif"
                                            placeholder="Email" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Outlet Phone<span class="required"
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
                                    <label class="control-label">Outlet Code<span class="required"
                                            aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Outlet Code"
                                            data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="outlet_code"
                                            value="@if (isset($detail['outlet_code'])) {{ $detail['outlet_code'] }} @endif"
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
                                            @if ($detail['status'] == "Active") checked @endif>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group" id="admin-role-selection">
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
                                            <option value="">--Select--</option>
                                            <option value="1">Partner 1</option>
                                            <option value="2">Partner 2</option>
                                            <option value="3">Partner 3</option>
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
                                        <i class="fa fa-question-circle tooltips" data-original-title="Activities"
                                            data-container="body"></i>
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

                    </div>
                    <div class="form-actions">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <hr style="width:95%; margin-left:auto; margin-right:auto; margin-bottom:20px">
                            </div>
                            <div class="col-md-offset-5 col-md-2 text-center">
                                <button type="submit" class="btn yellow btn-block"><i class="fa fa-check"></i>
                                    Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
