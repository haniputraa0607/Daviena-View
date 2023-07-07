<?php
    use App\Lib\MyHelper;
?>

@extends('layouts.main')

@section('page-style')
@endsection


@section('page-script')
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

    <div class="portlet light" style="padding: 0px;">
        <div class="portlet-body m-form__group row">
            <div class="col-md-12">
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-2">
                            <a href="{{ url('browse/mobile-user') }}/{{ $user_id }}" class="btn btn-outline blue"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <tr id="transaction">
            <td colspan="2">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject font-dark sbold uppercase font-blue">Transaction Detail</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form class="form-horizontal" role="form">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3">Transaction Status</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            @if ($details['transaction_status'] == 'Completed')
                                                <span class="caption-subject sbold badge badge-success badge-sm">{{ $details['transaction_status'] }}</span>
                                            @elseif ($details['transaction_status'] == 'Canceled')
                                                <span class="caption-subject sbold badge badge-danger badge-sm">{{ $details['transaction_status'] }}</span>
                                            @elseif ($details['transaction_status'] == 'Pending')
                                                <span class="caption-subject sbold badge badge-warning badge-sm">{{ $details['transaction_status'] }}</span>
                                            @elseif ($details['transaction_status'] == 'Waiting')
                                                <span class="caption-subject sbold badge badge-warning badge-sm">{{ $details['transaction_status'] }}</span>
                                            @elseif ($details['transaction_status'] == 'Paid')
                                                <span class="caption-subject sbold badge badge-info badge-sm">{{ $details['transaction_status'] }}</span>
                                            @else
                                                <span class="caption-subject sbold badge badge-default badge-sm">{{ $details['transaction_status'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Transaction Code</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold font-blue">{{ $details['transaction_code'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Transaction Date</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon left">
                                            <span class="caption-subject font-dark sbold">{{ MyHelper::indonesian_date($details['date']) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Order Time</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold">{{ $details['order_time'] == "" ? 0 : $details['order_time'] }} minutes</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Charging Time</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold">{{ $details['charging_time'] == "" ? 0 : $details['charging_time'] }} minutes</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Time Left</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold">{{ $details['remaining_time'] == "" ? 0 : $details['remaining_time']}} minutes</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Payment Status</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            @if ($details['payment_status'] == 'Completed')
                                                <span class="caption-subject sbold badge badge-success badge-sm">{{ $details['payment_status'] }}</span>
                                            @elseif ($details['payment_status'] == 'Canceled')
                                                <span class="caption-subject sbold badge badge-danger badge-sm">{{ $details['payment_status'] }}</span>
                                            @elseif ($details['payment_status'] == 'Pending')
                                                <span class="caption-subject sbold badge badge-warning badge-sm">{{ $details['payment_status'] }}</span>
                                            @elseif ($details['payment_status'] == 'Waiting')
                                                <span class="caption-subject sbold badge badge-warning badge-sm">{{ $details['payment_status'] }}</span>
                                            @elseif ($details['payment_status'] == 'Paid')
                                                <span class="caption-subject sbold badge badge-info badge-sm">{{ $details['payment_status'] }}</span>
                                            @else
                                                <span class="caption-subject sbold badge badge-default badge-sm">{{ $details['payment_status'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Payment Method</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold">{{ $details['payment_type'] == "" ? "-" : $details['payment_type'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Price</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold">Rp. {{ number_format(floatval($details['gross_amount']), 2, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Pay</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold">Rp. {{ number_format(floatval($details['pay']), 2, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Cas Point Used</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold">{{ number_format(floatval($details['cas_point_used']), 2, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Returned to Cas Point</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold">{{ number_format(floatval($details['return_cas_point']), 2, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </td>
        </tr>
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
                                    <label class="col-md-3">ECS Code</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold">{{ $device['ecs_id'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Connector Number</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold">{{ $device['connector_id'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </td>
            <td style="width:50%">
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
                                            <span class="caption-subject font-dark sbold">{{ $device['location_name'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Description</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold">{{ $device['location_description'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Address</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold">{{ $device['location_address'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Type</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            @if (!empty($device['location_type']))
                                                    @if ($device['location_type'] == "BW")
                                                        <span class="caption-subject font-dark sbold">Business / Workplace</span>
                                                    @elseif ($device['location_type'] == "CP")
                                                        <span class="caption-subject font-dark sbold">Commercial Place</span>
                                                    @elseif ($device['location_type'] == "FM")
                                                        <span class="caption-subject font-dark sbold">Fleet Management</span>
                                                    @elseif ($device['location_type'] == "RA")
                                                        <span class="caption-subject font-dark sbold">Residential Area</span>
                                                    @else
                                                        <span class="caption-subject font-dark sbold font-red">Unknown</span>
                                                    @endif
                                                @else
                                                    <span class="caption-subject font-dark sbold">-</span>
                                                @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Code</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold">{{ $device['building_code'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Developer</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div class="input-icon right">
                                            <span class="caption-subject font-dark sbold">{{ $device['developer_name'] }} ({{ $device['developer_code'] }})</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </td>
        </tr>
    </table>
@endsection
