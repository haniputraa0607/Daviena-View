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

        @if (!empty($detail['latitude']))
        var lat = "{{$detail['latitude']}}";
        @else
        var lat = "-7.7972";
        @endif

        @if (!empty($detail['longitude']))
        var long = "{{$detail['longitude']}}";
        @else
        var long = "110.3688";
        @endif

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
            var SweetAlertDetail = function() {
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
                                                        '<input type="hidden" name="image_id[]" value="">'+
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
                    SweetAlertDetail.init()
                }
            });

            $('.btn_add_image').on('click', function(event) {
                $('.image-sortable').append(new_sortable_item);
                $('.btn-delete').on('click', function() {
                    if ($('.portlet-sortable').length > 1) {
                        $(this).closest('.portlet-sortable').remove();
                    } else {
                        SweetAlertDetail.init()
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
    <script type="text/javascript">
        var SweetAlert = function() {
                return {
                    init: function() {
                        $(".sweetalert-delete-device").each(function() {
                            var token  	        = "{{ csrf_token() }}";
                            let column 	        = $(this).parents('tr');
                            let id     	        = $(this).data('id');
                            let location_id     = $(this).data('id-location');
                            let location_name   = $(this).data('name-location');
                            let code            = $(this).data('code');
                            var location_delete = "{{ url('browse/location/delete/device')}}";
                            $(this).click(function() {
                                swal({
                                        title: "Are you sure want to delete device "+code+" from "+location_name+"?",
                                        text: "Your will not be able to recover this data!",
                                        type: "warning",
                                        showCancelButton: true,
                                        confirmButtonClass: "btn-danger",
                                        confirmButtonText: "Yes, delete it!",
                                        closeOnConfirm: false
                                    },
                                    function(){
                                        window.location.href = location_delete+'/'+location_id+'/'+id;
                                        // $.ajax({
                                        //     type : "GET",
                                        //     url : "{{ url('browse/vehicle/type/delete') }}"+'/'+id,
                                        //     success : function(result) {
                                        //         if (result.status == "success") {
                                        //             swal({
                                        //                 title: 'Deleted!',
                                        //                 text: 'Vehicle type has been deleted.',
                                        //                 type: 'success',
                                        //                 showCancelButton: false,
                                        //                 showConfirmButton: false
                                        //             })
                                        //             SweetAlert.init()
                                        //             window.location.reload(true);
                                        //         } else if(result.status == "fail"){
                                        //             swal("Error!", result.messages[0], "error")
                                        //         } else {
                                        //             swal("Error!", "Something went wrong. Failed to delete vehicle brand.", "error")
                                        //         }
                                        //     }
                                        // });
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

    <script type="text/javascript">
        var SweetAlert = function() {
                return {
                    init: function() {
                        $(".sweetalert-delete-location").each(function() {
                            var token  	        = "{{ csrf_token() }}";
                            let id     	        = $(this).data('id');
                            let location_name   = $(this).data('name-location');
                            var location_delete_url = "{{ url('browse/location/delete')}}";
                            $(this).click(function() {
                                swal({
                                        title: "Are you sure want to delete "+location_name+"?",
                                        text: "Your will not be able to recover this data!",
                                        type: "warning",
                                        showCancelButton: true,
                                        confirmButtonClass: "btn-danger",
                                        confirmButtonText: "Yes, delete it!",
                                        closeOnConfirm: false
                                    },
                                    function(){
                                        window.location.href = location_delete_url+'/'+id;
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

            //input type number for older browser prevent char
            $('#num_of_connector').on('keypress', function(event) {
                if (event.which < 48 || event.which > 57) {
                    event.preventDefault();
                }
            });
        });
    </script>
    <script>
        function selectEcs(id, ecs_id) {
            $('#modalAssignECS').modal('hide');
            $('#id_ecs').val(id);
            $('#ecs_selected_label').empty().show().addClass("form-control").append('<option>'+ecs_id+'</option>');
        };
    </script>
    <script>
        var search_ecs = function() {
            return {
                init: function() {
                        $("#search_ecs").click(function() {
                            let search 	= $('input[name="search_field"]').val()
                            $.ajax({
                                type : "GET",
                                url : "{{ url('browse/ecs/search') }}"+'?search='+search,
                                success : function(result) {
                                    if (result.status == "success") {
                                        let html = "";
                                        $('#ecs_select').empty();
                                        if (result.data.length > 0) {
                                            $.each(result.data, function(index, ecs) {
                                                if (ecs.status == 'NEW') {
                                                    status = '<span class="badge badge-success badge-pill">New</span>';
                                                } else if (ecs.status == 'ACTIVE') {
                                                    status = '<span class="badge badge-primary badge-pill">Active</span>';
                                                } else {
                                                    status = '<span class="badge badge-default badge-pill">Inactive</span>';
                                                }
                                                html += '<tr style="text-align: center">'+
                                                                '<td>'+ecs.ecs_id+'</td>'+
                                                                '<td>'+status+'</td>'+
                                                                '<td style="width: 90px;">'+
                                                                    `<a class="btn btn-sm blue" onclick="selectEcs('`+ecs.id+`','`+ecs.ecs_id+`')">Select</a>`+
                                                                '</td>'+
                                                            '</tr>';
                                            });
                                        } else {
                                            html = '<tr><td colspan="3">No ECS Found</td></tr>';
                                        }
                                        $('#ecs_select').append(html);
                                    } else if(result.status == "fail"){
                                        toastr.warning(result.messages[0]);
                                    } else {
                                        toastr.warning("Something went wrong.");
                                    }
                                }
                            });
                        })
                }
            }
        }();

        jQuery(document).ready(function() {
            search_ecs.init()
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
                <span class="caption-subject font-blue sbold uppercase ">Location Detail</span>
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
                                        <input width="100px;" type="checkbox" class="make-switch" data-size="small" data-on-color="info" data-on-text="Active" data-off-color="default" data-off-text="Nonactive" name="status" value="1" @if($detail['status']??'') checked @endif>
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
                                        <input type="text" class="form-control" name="name" @if (isset($detail['name'])) value="{{ $detail['name'] }}" @else value="{{ old('name') }}" @endif placeholder="Nama Lokasi" required>
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
                                        <input type="text" class="form-control must-upper" name="building_code" pattern=".{3}" maxlength="3" placeholder="Kode Gedung" @if (isset($detail['building_code'])) value="{{ $detail['building_code'] }}" @else value="{{ old('building_code') }}" @endif required>
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
                                            <option value="BW" {{ $detail['location_type'] == "BW" ? "selected" : "" }}>Business / Workplace</option>
                                            <option value="CP" {{ $detail['location_type'] == "CP" ? "selected" : "" }}>Commercial Place</option>
                                            <option value="FM" {{ $detail['location_type'] == "FM" ? "selected" : "" }}>Fleet Management</option>
                                            <option value="RA" {{ $detail['location_type'] == "RA" ? "selected" : "" }}>Residential Area</option>
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
                                                <option value="{{ $developer['id'] }}" {{ $detail['developer']['id'] == $developer['id'] ? "selected" : "" }}>{{ $developer['name'] }} ({{ $developer['developer_code'] }})</option>
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
                                        <input type="text" class="form-control" name="description" @if (isset($detail['description'])) value="{{ $detail['description'] }}" @else value="{{ old('description') }}" @endif placeholder="Deskripsi Lokasi" required>
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
                                        <textarea name="address" class="form-control" rows="5" placeholder="Alamat Lokasi" required>@if(isset($detail['address'])){{ $detail['address'] }}@else{{ old('address') }}@endif</textarea>
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
                                            <input type="time" class="form-control featureTimeForm" name="time_start" id="time_start" @if (isset($detail['time_start'])) value="{{ $detail['time_start'] }}" @else value="{{ old('time_start') }}" @endif required>
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
                                            <input type="time" class="form-control featureTimeForm" name="time_end" @if (isset($detail['time_end'])) value="{{ $detail['time_end'] }}" @else value="{{ old('time_end') }}" @endif required>
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
                                        @if (count($detail['location_image']) > 0)
                                            @foreach ($detail['location_image'] as $image)
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
                                                                    <input type="hidden" name="image_id[]" value="{{ $image['id'] }}">
                                                                    <img class="previewImage" src="{{ env('STORAGE_URL_API')}}{{ $image['path'] }}" alt="">
                                                                </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail" id="image_landscape" style="width: 160px; height: 120px;"></div>
                                                                <div class="btnImage">
                                                                    <span class="btn default btn-file">
                                                                    <span class="fileinput-new"> Select image </span>
                                                                    <span class="fileinput-exists"> Change </span>
                                                                    <input type="file" id="0" accept="image/png, image/jpeg" class="file form-control demo featureImageForm" name="file[]">
                                                                    </span>
                                                                    <a href="javascript:;" id="removeImage0" class="btn red fileinput-exists btremove" data-dismiss="fileinput"> Remove </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
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
                                                                <input type="hidden" name="image_id[]" value="">
                                                                <img class="previewImage" src="https://via.placeholder.com/160x120/EFEFEF?text=1280x960" alt="">
                                                            </div>
                                                            <div class="fileinput-preview fileinput-exists thumbnail" id="image_landscape" style="width: 160px; height: 120px;"></div>
                                                            <div class="btnImage">
                                                                <span class="btn default btn-file">
                                                                <span class="fileinput-new"> Select image </span>
                                                                <span class="fileinput-exists"> Change </span>
                                                                <input type="file" id="0" accept="image/png, image/jpeg" class="file form-control demo featureImageForm" name="file[]">
                                                                </span>
                                                                <a href="javascript:;" id="removeImage0" class="btn red fileinput-exists btremove" data-dismiss="fileinput"> Remove </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
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
                                <div class="col-md-2">
                                    <label class="control-label"></label>
                                </div>
                                <div class="col-md-10">
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
                            <div class="col-md-offset-5 col-md-2">
                                <input type="hidden" name="id" value="{{ $detail['id'] }}">
                                <button type="submit" class="btn yellow"><i class="fa fa-save"></i> Update</button>
                            </div>
                            <div class="col-md-2">
                                <a class="btn red sweetalert-delete-location btn-primary" data-id="{{ $detail['id'] }}" data-name-location="{{ $detail['name'] }}"><i class="fa fa-trash-o"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-dark sbold uppercase font-blue">Assign New ECS</span>
            </div>
        </div>
        <div class="portlet-body form">
            <form class="form-horizontal" action="{{ url('browse/ecs/assign-location') }}" method="post"  role="form">
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Connectors<span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-7">
                            <div class="input-icon right">
                                <input type="hidden" name="location_id" id="location_id" value="{{ $detail['decrypted_location_id'] }}">
                                <input type="number" placeholder="Number of Connectors" class="form-control" name="num_of_connector" id="num_of_connector" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">ECS<span class="required" aria-required="true">*</span>
                        </label>
                        <div class="col-md-7">
                            <div class="input-icon right top" id="ecs_select_input">
                                <input type="hidden" name="id_ecs" id="id_ecs" value="">
                                <select name="ecs_selected_label" id="ecs_selected_label" style="margin-bottom: 20px;" disabled hidden required></select>
                                <a href="#modalAssignECS" class="btn btn-sm blue assign-ecs" data-toggle="modal" style="margin-bottom: 15px;">
                                    <i class="fa fa-plug"></i> Select ECS
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-offset-5 col-md-2">
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue sbold uppercase">ECS</span>
            </div>
        </div>
        <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                <thead>
                    <tr >
                        <th style="text-align: center"> ID </th>
                        <th style="text-align: center"> Connector </th>
                        <th style="text-align: center"> Action </th>
                    </tr>
                </thead>
                <tbody id="type">
                    @if (!empty($ecs))
                        @foreach ($ecs as $device)
                            <tr style="text-align: center">
                                <td>{{ $device['ecs_id'] }}</td>
                                <td>
                                    {{ count($device['conector_list']) }}
                                </td>
                                <td>
                                    <a class="btn btn-sm blue btn-primary" href="{{ url('browse/ecs/'.$device['id']) }}"><i class="fa fa-search"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr  style="text-align: center"><td colspan="4">No Data</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalAssignECS" tabindex="-1"  role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Select ECS</h4>
                </div>
                <div class="modal-body form">
                    <div class="form-body">
                        <div class="row" style="padding-bottom: 20px;">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="search_field" name="search_field" placeholder="Search ECS" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-sm blue search_ecs" id="search_ecs">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-hover dt-responsive" width="90%" id="table_data">
                                    <thead>
                                        <tr style="text-align: center">
                                            <th> ECS ID </th>
                                            <th> STATUS </th>
                                            <th> Action </th>
                                        </tr>
                                    </thead>
                                    <tbody id="ecs_select">
                                        @if (!empty($ecs_option))
                                            @foreach ($ecs_option as $option)
                                                <tr style="text-align: center">
                                                    <td>{{ $option['ecs_id'] }}</td>
                                                    <td>
                                                        @if ($option['status'] == 'NEW')
                                                            <span class="badge badge-success badge-pill">New</span>
                                                        @elseif ($option['status'] == 'ACTIVE')
                                                            <span class="badge badge-primary badge-pill">Active</span>
                                                        @else
                                                            <span class="badge badge-default badge-pill">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td style="width: 90px;">
                                                        <a class="btn btn-sm blue" onclick="selectEcs('{{ $option['id'] }}','{{ $option['ecs_id'] }}')">Select</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" style="text-align: center;">No Data</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
