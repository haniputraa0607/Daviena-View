@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/datemultiselect/jquery-ui.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-summernote/summernote.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/datemultiselect/jquery-ui.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCOHBNv3Td9_zb_7uW-AJDU6DHFYk-8e9Y&v=3.exp&signed_in=true&libraries=places"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script>
        var map;

        var lat = "-7.7972";
        var long = "110.3688";

        var markers = [];

        function initialize() {
        var haightAshbury = new google.maps.LatLng(lat,long);
        var marker        = new google.maps.Marker({
            position:new google.maps.LatLng(lat,long),
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

        var input = /** @type  {HTMLInputElement} */(
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
                map.setZoom(17);  // Why 17? Because it looks good.
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
        infowindow.open(map,marker);
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
        $(document).ready(function(){
            var SweetAlert = function() {
                return {
                    init: function() {
                        swal({
                            title: "Photo cannot be empty!",
                            text: "There must be at least 1 photo of location",
                            type: "error",
                            confirmButtonClass: "btn-info",
                            confirmButtonText: "Ok",
                            closeOnConfirm: true
                        });
                    }
                }
            }();
            var new_sortable_item = '<div class="portlet portlet-sortable light bordered col-md-6">'+
                                        '<div class="portlet-body">'+
                                            '<div class="col-md-6 text-left" style="padding-bottom:10px;">'+
                                                    '<i class="fa fa-arrows tooltips" data-original-title="Ubah urutan dengan drag n drop" data-container="body"></i>'+
                                            '</div>'+
                                            '<div class="col-md-6 text-right" style="padding-bottom:10px;">'+
                                                '<a class="btn red-mint btn-circle btn-delete"><i class="fa fa-trash-o"></i></a>'+
                                            '</div>'+
                                            '<div class="col-md-12 text-center">'+
                                                '<div class="fileinput fileinput-new" data-provides="fileinput">'+
                                                    '<div class="fileinput-new thumbnail" style="width: 160px; height: 120px;">'+
                                                        '<img class="previewImage" src="https://via.placeholder.com/160x120/EFEFEF?text=1280x960" alt="">'+
                                                    '</div>'+
                                                    '<div class="fileinput-preview fileinput-exists thumbnail" id="image_landscape" style="width: 160px; height: 120px;"></div>'+
                                                    '<div class="btnImage">'+
                                                        '<span class="btn default btn-file">'+
                                                        '<span class="fileinput-new"> Select image </span>'+
                                                        '<span class="fileinput-exists"> Change </span>'+
                                                        '<input type="file" id="0" accept="image/png, image/jpeg" class="file form-control demo featureImageForm" name="file[]" required>'+
                                                        '</span>'+
                                                        '<a href="javascript:;" id="removeImage0" class="btn red fileinput-exists btremove" data-dismiss="fileinput"> Remove </a>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'

            $( ".image-sortable" ).sortable();
            $( ".image-sortable" ).disableSelection();
            $('.btn-delete').on('click', function() {
                if ($('.portlet-sortable').length > 1) {
                    $(this).closest('.portlet-sortable').remove();
                } else {
                    SweetAlert.init()
                }
            });

            $('.btn_add_image').on('click', function(event) {
                $('.image-sortable').append(new_sortable_item);
                $('.btn-delete').on('click', function() {
                    if ($('.portlet-sortable').length > 1) {
                        $(this).closest('.portlet-sortable').remove();
                    } else {
                        SweetAlert.init()
                    }
                });
                $(".file").change(function(e) {
                    var _URL = window.URL || window.webkitURL;
                    var image, file;

                    btnRemove = $(this).parents().siblings('.btremove');

                    if ((file = this.files[0])) {
                        image = new Image();

                        image.onload = function() {
                            if (this.width != 1280 || this.height != 960) {
                                toastr.warning("Please check dimension of your image.");
                                btnRemove.trigger( "click" );
                            }
                        };
                        image.src = _URL.createObjectURL(file);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.must-upper').on('input', function() {
                $(this).val($(this).val().toUpperCase()); // Convert input value to uppercase using jQuery
            });

            $(".file").change(function(e) {
                var _URL = window.URL || window.webkitURL;
                var image, file;

                btnRemove = $(this).parents().siblings('.btremove');

                if ((file = this.files[0])) {
                    image = new Image();

                    image.onload = function() {
                        if (this.width != 1280 || this.height != 960) {
                            toastr.warning("Please check dimension of your image.");
                            btnRemove.trigger( "click" );
                        }
                    };
                    image.src = _URL.createObjectURL(file);
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
                <span class="caption-subject font-blue sbold uppercase ">New Location</span>
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
                                        <i class="fa fa-question-circle tooltips" data-original-title="Status Lokasi" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-12">
                                        <input type="checkbox" class="make-switch form-control" data-size="small" data-on-color="info" data-on-text="Active" data-off-color="default" data-off-text="Nonactive" name="status" value="1" checked>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Name<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Nama Lokasi" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="name" placeholder="Nama Lokasi" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Building Code<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Kode Gedung terdiri dari 3 huruf" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control must-upper" name="building_code" pattern=".{3}" maxlength="3" placeholder="Kode Gedung" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Location Type<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Tipe Lokasi" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <select name="location_type" class="form-control" required>
                                            <option value="">--Select--</option>
                                            <option value="BW">Business / Workplace</option>
                                            <option value="CP">Commercial Place</option>
                                            <option value="FM">Fleet Management</option>
                                            <option value="RA">Residential Area</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Developer<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Developer Properti" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <select name="developer_id" class="form-control" required>
                                            <option value="">--Select--</option>
                                            @foreach ($developers as $developer)
                                                <option value="{{ $developer['id'] }}">{{ $developer['name'] }} ({{ $developer['developer_code'] }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Description<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Deskripsi Lokasi" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="description" placeholder="Deskripsi Lokasi" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Address<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Alamat Lokasi" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <textarea name="address" class="form-control" rows="5" placeholder="Alamat Lokasi" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Opening Hour<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Jam mulai operasional lokasi" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <input type="time" class="form-control featureTimeForm" name="time_start" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group featureTime">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Closing Hour<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Jam akhir operasional lokasi" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <input type="time" class="form-control featureTimeForm" name="time_end" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 30px;">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Photo<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Foto Lokasi (minimal 1 foto) dengan ekstensi png/jpg" data-container="body"></i>
                                        <br>
                                        <span class="required" aria-required="true">(1280*960)</span>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-12 text-left" style="padding-bottom:10px;"><a class="btn btn-success btn_add_image"><i class="fa fa-plus"></i> Add Image</a></div>
                                    <div class="col-md-12 image-sortable">
                                        <div class="portlet portlet-sortable light bordered col-md-6">
                                            <div class="portlet-body">
                                                <div class="col-md-6 text-left" style="padding-bottom:10px;">
                                                    <i class="fa fa-arrows tooltips" data-original-title="Ubah urutan dengan drag n drop" data-container="body"></i>
                                                </div>
                                                <div class="col-md-6 text-right" style="padding-bottom:10px;">
                                                    <a class="btn red-mint btn-circle btn-delete"><i class="fa fa-trash-o"></i></a>
                                                </div>
                                                <div class="col-md-12 text-center">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail" style="width: 160px; height: 120px;">
                                                            <img class="previewImage" src="https://via.placeholder.com/160x120/EFEFEF?text=1280x960" alt="">
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail" id="image_landscape" style="width: 160px; height: 120px;"></div>
                                                        <div class="btnImage">
                                                            <span class="btn default btn-file">
                                                            <span class="fileinput-new"> Select image </span>
                                                            <span class="fileinput-exists"> Change </span>
                                                            <input type="file" id="0" accept="image/png, image/jpeg" class="file form-control demo featureImageForm" name="file[]" required>
                                                            </span>
                                                            <a href="javascript:;" id="removeImage0" class="btn red fileinput-exists btremove" data-dismiss="fileinput"> Remove </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group featureLocation" style="margin-top: 30px;">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Pin Point<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Pin point lokasi agar mudah ditemukan melalui map" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <input id="pac-input"  id="field_event_map" class="controls field_event" type="text" placeholder="Enter a location" style="padding:10px;width:50%" onkeydown="if (event.keyCode == 13) return false;" name="location_map">
                                    <div id="map-canvas" style="width:100%;height:380px;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group featureLocation">
                            <label class="col-md-2 control-label"></label>
                            <div class="col-md-10">
                                <div class="col-md-3">
                                    <label class="control-label"></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="latitude" id="lat" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="longitude" id="lng" readonly>
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
                            <div class="col-md-offset-6 col-md-4">
                                <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
