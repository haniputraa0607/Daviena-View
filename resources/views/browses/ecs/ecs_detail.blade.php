@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script>
        function handleUnknownReference() {
            swal("Something went wrong!", "Unknown Reference to Stop Charging by CMS.", "error");
        }
    </script>
    <script>
        function handleStartChargingClick(clickedElement) {
            clearInterval(intervalId);
            $(clickedElement).hide();

            var secondButton = $('<a>', {
                class: 'btn btn-sm blue start-charging',
                alt: 'Start Charging',
                style: 'width:50px;',
                disabled: true
            }).append($('<i>', { class: 'fa fa-spinner fa-spin' }));

            $(clickedElement).after(secondButton);
        }
    </script>
    @if (!empty($connector_list))
        <script>
            var intervalId;
            $(document).ready(function() {
                updateLastFetchTime();
                var refreshInterval = 3000; // Refresh every x/1000 seconds
                var reloadInterval = 15; // Reload after fetch x times
                $("#interval-time").text("*The data is fetched every "+ (refreshInterval/1000) +" seconds. (auto-reload after "+ reloadInterval +" times)"); //set table foot note
                var fetchCount = 0;

                function fetchData() {
                    $.ajax({
                        url: "{{ url('browse/ecs/ajax/connectors') }}/{{ $device_detail['id'] }}",
                        type: "GET",
                        dataType: "json",
                        success: function(result) {
                            if (result.status == 'success') {
                                // Clear existing table rows
                                $("#connector-list").empty();

                                // Iterate over the received data and append new rows to the table
                                if (result.data && result.data.length > 0) {
                                    $.each(result.data, function(index, connector) {
                                        var qr_code_url = "{{ url('browse/ecs/qrcode') }}/{{ $device_detail['id'] }}" + "/" + connector.connector_id;
                                        var start_charging_url = "{{ url('browse/ecs/start-charging') }}/{{ $device_detail['ecs_id'] }}" + "/" + connector.connector_id;
                                        var stop_charging_url = "{{ url('browse/ecs/stop-charging') }}/{{ $device_detail['ecs_id'] }}" + "/" + connector.connector_id + "/" + connector.used_by;
                                        var row = $("<tr>").appendTo("#connector-list");

                                        $('<td style="text-align: center">').text(connector.connector_id).appendTo(row);
                                        $('<td style="text-align: center">').html('<a href="' + qr_code_url + '" class="btn btn-sm blue" target="_blank" rel="noopener noreferrer"><i class="fa fa-qrcode"></i></a>').appendTo(row);

                                        //status column start
                                            var status = $('<td style="text-align: center">');
                                            if (connector.status === "AVAILABLE") {
                                                status.html('<span class="badge badge-success" style="width:100px;">Available</span>');
                                            } else if (connector.status === "CONNECTED") {
                                                status.html('<span class="badge badge-primary" style="width:100px;">Connected</span>');
                                            } else if (connector.status === "CHARGING") {
                                                status.html('<span class="badge badge-info" style="width:100px;">Charging</span>');
                                            } else if (connector.status === "CHARGING-STOP") {
                                                status.html('<span class="badge badge-warning" style="width:100px;">Charging Stop</span>');
                                            } else if (connector.status === "DISCONNECTED") {
                                                status.html('<span class="badge badge-danger" style="width:100px;">Disconnected</span>');
                                            } else {
                                                status.html('<span class="badge badge-default">Unknown</span>');
                                            }
                                            status.appendTo(row);
                                        //status column end

                                        //action column start
                                            var action = $('<td style="text-align: center">');
                                            if (connector.status === "CONNECTED") {
                                                action.html('<a href="' + start_charging_url + '" class="btn btn-sm blue" alt="Start Charging" style="width:50px;" onclick="handleStartChargingClick(this)"><i class="fa fa-bolt"></i></a>');
                                            } else if (connector.status === "CHARGING") {
                                                if (connector.used_by != "") {
                                                    action.html('<a href="' + stop_charging_url + '" class="btn btn-sm red" alt="Stop Charging" style="width:50px;"><i class="fa fa-power-off"></i></a>');
                                                } else {
                                                    action.html('<a class="btn btn-danger" alt="Stop Charging" style="width:50px;" onclick="handleUnknownReference()"><i class="fa fa-power-off"></i></a>');
                                                }
                                            }
                                            action.appendTo(row);
                                        //action column end

                                        $('<td style="text-align: left">').text(connector.used_by ? connector.used_by : "-").appendTo(row);
                                    });
                                } else {
                                    // If no data is available, display a message
                                    var row = $("<tr>").appendTo("#connector-list");
                                    $("<td colspan='5'>").text("No Data").appendTo(row);
                                }
                            } else {
                                toastr.warning("Error Fetching Data: " + result.messages);
                                clearInterval(intervalId); // Stop the interval
                            }
                        },
                        error: function(xhr, status, error) {
                            toastr.warning("Error Fetching Data: " + result.messages);
                            clearInterval(intervalId); // Stop the interval
                        },
                        complete: function() {
                            fetchCount++;
                            if (fetchCount === reloadInterval) {
                                location.reload(); // Reload the page
                            }
                        }
                    });
                }

                function updateLastFetchTime() {
                    var currentTime = new Date();
                    var options = { timeZone: 'Asia/Bangkok', dateStyle: 'full', timeStyle: 'medium' };
                    var formattedTime = currentTime.toLocaleString('en-US', options);
                    $("#last-fetch").html("Last Fetch: <b>" + formattedTime + "</b>");
                }

                intervalId = setInterval(function() {
                    fetchData();
                    updateLastFetchTime();
                }, refreshInterval);
            });
        </script>
    @endif
    @if ($device_detail['status'] == 'NEW')
        <script>
            function selectLocation(id, name) {
                $('#modalAssignLocation').modal('hide');
                $('#location_id').val(id);
                $('#location_selected_label').empty().show().addClass("form-control").append('<option>'+name+'</option>');
            };
        </script>
        <script>
            var search_location = function() {
                return {
                    init: function() {
                            $("#search_location").click(function() {
                                let search 	= $('input[name="search_field"]').val()
                                $.ajax({
                                    type : "GET",
                                    url : "{{ url('browse/location/search') }}"+'?search='+search,
                                    success : function(result) {
                                        if (result.status == "success") {
                                            let html = "";
                                            $('#location_select').empty();
                                            if (result.data.length > 0) {
                                                $.each(result.data, function(index, location) {
                                                    if (location.location_type == 'BW') {
                                                        type = 'Business / Workplace';
                                                    } else if (location.location_type == 'CP') {
                                                        type = 'Commercial Place';
                                                    } else if (location.location_type == 'FM') {
                                                        type = 'Fleet Management';
                                                    } else if (location.location_type == 'RA') {
                                                        type = 'Residential Area';
                                                    } else {
                                                        type = 'Unknown';
                                                    }
                                                    html += '<tr>'+
                                                                    '<td>'+location.name+'</td>'+
                                                                    '<td>'+location.description+'</td>'+
                                                                    '<td>'+location.address+'</td>'+
                                                                    '<td>'+type+'</td>'+
                                                                    '<td>'+location.building_code+'</td>'+
                                                                    '<td>'+location.Developer.name + ' (' + location.Developer.developer_code + ')</td>'+
                                                                    '<td style="width: 90px;">'+
                                                                        `<a class="btn btn-sm blue" onclick="selectLocation('`+location.id+`','`+location.name+`')">Select</a>`+
                                                                    '</td>'+
                                                                '</tr>';
                                                });
                                            } else {
                                                html = '<tr><td colspan=7>No Location Found</td></tr>';
                                            }
                                            $('#location_select').append(html);
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
                search_location.init()

                //input type number for older browser prevent char
                $('#num_of_connector').on('keypress', function(event) {
                    if (event.which < 48 || event.which > 57) {
                        event.preventDefault();
                    }
                });
            });
        </script>
    @endif
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

    <table class="table table-bordered">
        <tr id="detail">
            <td style="width:50%">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject font-dark sbold uppercase font-blue">ECS Detail</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form class="form-horizontal" role="form">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3">Status</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            @if ($device_detail['status'] == 'NEW')
                                                <span class="badge badge-success caption-subject sbold">New</span>
                                            @elseif ($device_detail['status'] == 'ACTIVE')
                                                <span class="badge badge-primary caption-subject sbold">Active</span>
                                            @else
                                                <span class="badge badge-default caption-subject sbold">Inactive</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">ECS Code</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold font-blue">{{ $device_detail['ecs_id'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Connector</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold font-blue">{{ $device_detail['num_of_connector'] == 0 ? "-" : $device_detail['num_of_connector'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    @if ($device_detail['status'] != 'NEW')
                                        <div class="col-md-3">
                                            <a href="{{ url('browse/ecs/qrcode/') }}/{{ $device_detail['id'] }}" class="btn btn-sm red" target="_blank" rel="noopener noreferrer"><i class="fa fa-print"></i> Print QR</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </td>
            <td style="width:50%">
                @if ($device_detail['status'] == 'NEW')
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <span class="caption-subject font-dark sbold uppercase font-blue">Assign to Location</span>
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
                                                <input type="hidden" name="id_ecs" value="{{ $device_detail['id'] }}">
                                                <input type="number" placeholder="Num of Connectors" class="form-control" name="num_of_connector" id="num_of_connector" min="1" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Location<span class="required" aria-required="true">*</span>
                                        </label>
                                        <div class="col-md-7">
                                            <div class="input-icon right top" id="location_select_input">
                                                <input type="hidden" name="location_id" id="location_id" value="" required>
                                                <select name="location_selected_label" id="location_selected_label" style="margin-bottom: 20px;" disabled hidden required></select>
                                                <a href="#modalAssignLocation" class="btn btn-sm blue assign-location" data-toggle="modal" style="margin-bottom: 15px;">
                                                    <i class="fa fa-map-marker"></i> Select Location
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-2">
                                            <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <span class="caption-subject font-dark sbold uppercase font-blue">Location</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <form class="form-horizontal" role="form">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3">Name</label>
                                        <label class="col-md-1">:</label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <span class="caption-subject font-dark sbold font-blue">{{ $location_detail['name'] ?? "-" }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3">Description</label>
                                        <label class="col-md-1">:</label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <span class="caption-subject font-dark sbold font-blue">{{ $location_detail['description'] ?? "-" }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3">Address</label>
                                        <label class="col-md-1">:</label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <span class="caption-subject font-dark sbold font-blue">{{ $location_detail['address'] ?? "-" }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3">Type</label>
                                        <label class="col-md-1">:</label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                @if (!empty($location_detail['location_type']))
                                                    @if ($location_detail['location_type'] == "BW")
                                                        <span class="caption-subject font-dark sbold font-blue">Business / Workplace</span>
                                                    @elseif ($location_detail['location_type'] == "CP")
                                                        <span class="caption-subject font-dark sbold font-blue">Commercial Place</span>
                                                    @elseif ($location_detail['location_type'] == "FM")
                                                        <span class="caption-subject font-dark sbold font-blue">Fleet Management</span>
                                                    @elseif ($location_detail['location_type'] == "RA")
                                                        <span class="caption-subject font-dark sbold font-blue">Residential Area</span>
                                                    @else
                                                        <span class="caption-subject font-dark sbold font-red">Unknown</span>
                                                    @endif
                                                @else
                                                    <span class="caption-subject font-dark sbold font-blue">-</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3">Code</label>
                                        <label class="col-md-1">:</label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <span class="caption-subject font-dark sbold font-blue">{{ $location_detail['building_code'] ?? "-" }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3">Developer</label>
                                        <label class="col-md-1">:</label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                @if (!empty($location_detail))
                                                    @if ($location_detail['developer_id'] != 0)
                                                        <span class="caption-subject font-dark sbold font-blue">{{ $location_detail['Developer']['name'] . " (" . $location_detail['Developer']['developer_code'] . ")" }}</span>
                                                    @else
                                                        <span class="caption-subject font-dark sbold font-blue">-</span>
                                                    @endif
                                                @else
                                                    <span class="caption-subject font-dark sbold font-blue">-</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </td>
        </tr>
        <tr id="pricing">
            <td colspan="2">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject font-dark sbold uppercase font-blue">Pricing</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form class="form-horizontal" action="{{ url('browse/ecs/'.$device_detail['id'].'/pricing/update') }}" role="form" method="POST">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3">Price Group</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-4">
                                        <div class="input-icon right">
                                            <input type="hidden" name="id" value="{{ $device_detail['id'] }}">
                                            <select class="form-control" name="custom_price">
                                                <option value="0">Global</option>
                                                @foreach ($custom_prices as $price)
                                                <option value="{{ $price['id'] }}" @if ($price['id'] == $device_detail['pricing_group']) selected @endif>{{ $price['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn yellow"><i class="fa fa-save"></i> Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </td>
        </tr>
        <tr id="connector">
            <td colspan="2">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject font-dark sbold uppercase font-blue">Connector</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="col-md-offset-3 col-md-9" style="margin-bottom: 10px; text-align: right;">
                            <label id="last-fetch"></label>
                        </div>
                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                            <thead>
                                <tr style="text-align: center">
                                    <th style="text-align: center"> Number </th>
                                    <th style="text-align: center"> QR Code </th>
                                    <th style="text-align: center"> Status </th>
                                    <th style="text-align: center"> Action </th>
                                    <th > Used By </th>
                                </tr>
                            </thead>
                            <tbody id="connector-list">
                                @if (!empty($connector_list))
                                    @foreach ($connector_list as $connector)
                                        <tr style="text-align: center">
                                            <td>{{ $connector['connector_id'] }}</td>
                                            <td style="width: 90px;">
                                                <a href="{{ url('browse/ecs/qrcode') }}/{{ $device_detail['id'] }}/{{ $connector['connector_id'] }}" class="btn btn-sm blue" target="_blank" rel="noopener noreferrer"><i class="fa fa-qrcode"></i></a>
                                            </td>
                                            <td>
                                                @if ($connector['status'] == 'AVAILABLE')
                                                    <span class="badge badge-success" style="width:100px;">Available</span>
                                                @elseif ($connector['status'] == 'CONNECTED')
                                                    <span class="badge badge-primary" style="width:100px;">Connected</span>
                                                @elseif ($connector['status'] == 'CHARGING')
                                                    <span class="badge badge-info" style="width:100px;">Charging</span>
                                                @elseif ($connector['status'] == 'CHARGING-STOP')
                                                    <span class="badge badge-warning" style="width:100px;">Charging Stop</span>
                                                @elseif ($connector['status'] == 'DISCONNECTED')
                                                    <span class="badge badge-danger" style="width:100px;">Disconnected</span>
                                                @else
                                                    <span class="badge badge-default">Unknown</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($connector['status'] == 'AVAILABLE')
                                                    {{-- <a class="btn btn-sm green" alt="Connect Nozzle before start Charge" style="width:50px;" disabled><i class="fa fa-plug"></i></a> --}}
                                                @elseif ($connector['status'] == 'CONNECTED')
                                                    <a href="{{ url('browse/ecs/start-charging/') }}/{{ $device_detail['ecs_id'] }}/{{ $connector['connector_id'] }}" class="btn btn-sm blue" alt="Start Charging" style="width:50px;" onclick="handleStartChargingClick(this)"> <i class="fa fa-bolt"> </i> </a>
                                                @elseif ($connector['status'] == 'CHARGING')
                                                    @if ($connector['used_by'] != null)
                                                        <a href="{{ url('browse/ecs/stop-charging/') }}/{{ $device_detail['ecs_id'] }}/{{ $connector['connector_id'] }}/{{ $connector['used_by'] }}" class="btn btn-sm red" alt="Stop Charging" style="width:50px;"><i class="fa fa-power-off"></i></a>
                                                    @else
                                                        <a class="btn btn-danger" alt="Stop Charging" style="width:50px;" onclick="handleUnknownReference()"><i class="fa fa-power-off"></i></a>
                                                    @endif
                                                @elseif ($connector['status'] == 'CHARGING-STOP')
                                                    {{-- <a class="btn btn-sm red" alt="Please Unplug Nozzle" style="width:50px;" disabled><i class="fa fa-chain-broken"></i></a> --}}
                                                @elseif ($connector['status'] == 'DISCONNECTED')
                                                    {{-- <a class="btn btn-sm yellow" alt="Waiting Nozzle back to Available" style="width:50px;" disabled><i class="fa fa-clock-o"></i></a> --}}
                                                @else
                                                    <span class="badge badge-default">Unknown</span>
                                                @endif
                                            </td>
                                            <td style="text-align: left">
                                                {{ isset($connector['used_by']) ? ($connector['used_by'] != null ? $connector['used_by'] : "-") : "-" }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" style="text-align: center;">No Data</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <label id="interval-time" style="font-size: 11px;"></label><br>
                                        <label style="font-size: 11px;">*Before taking any action on the connector, make sure that the displayed data is the most recent.</label>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    @if ($device_detail['status'] == 'NEW')
        <div class="modal fade" id="modalAssignLocation" tabindex="-1"  role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Select Location</h4>
                    </div>
                    <div class="modal-body form">
                        <div class="form-body">
                            <div class="row" style="padding-bottom: 20px;">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" id="search_field" name="search_field" placeholder="Search Location" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-sm blue search_location" id="search_location">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover dt-responsive" width="90%" id="table_data">
                                        <thead>
                                            <tr style="text-align: center">
                                                <th> Name </th>
                                                <th> Description </th>
                                                <th> Address </th>
                                                <th> Type </th>
                                                <th> Code </th>
                                                <th> Developer </th>
                                                <th> Action </th>
                                            </tr>
                                        </thead>
                                        <tbody id="location_select">
                                            @if (!empty($location_option))
                                                @foreach ($location_option as $option)
                                                    <tr style="text-align: center">
                                                        <td>{{ $option['name'] }}</td>
                                                        <td>{{ $option['description'] }}</td>
                                                        <td>{{ $option['address'] }}</td>
                                                        <td>
                                                            @if ($option['location_type'] == "BW")
                                                                Business / Workplace
                                                            @elseif ($option['location_type'] == "CP")
                                                                Commercial Place
                                                            @elseif ($option['location_type'] == "FM")
                                                                Fleet Management
                                                            @elseif ($option['location_type'] == "RA")
                                                                Residential Area
                                                            @else
                                                                Unknown
                                                            @endif
                                                        </td>
                                                        <td>{{ $option['building_code'] }}</td>
                                                        <td>{{ $option['Developer']['name'] . " (" . $option['Developer']['developer_code'] . ")" }}</td>
                                                        <td style="width: 90px;">
                                                            <a class="btn btn-sm blue" onclick="selectLocation('{{ $option['id'] }}','{{ $option['name'] }}')">Select</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" style="text-align: center;">No Data</td>
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
    @endif
@endsection
