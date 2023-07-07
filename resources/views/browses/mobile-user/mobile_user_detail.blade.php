<?php
use App\Lib\MyHelper;
?>

@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .tooltip {
            z-index: 100000000;
        }
    </style>
@endsection


@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var SweetAlert = function() {
                return {
                    init: function() {
                        $(".button-action").each(function() {
                            var token  = "{{ csrf_token() }}";
                            let id     = $(this).data('id');
                            let email  = $(this).data('email');
                            let action = $(this).data('action');
                            let title
                            let text
                            let type
                            let confirmButtonClass
                            let confirmButtonText
                            if (action == 'activate') {
                                title = "Are you sure want to activate account "+email+"?"
                                type = "info"
                                confirmButtonClass = "btn-info"
                                confirmButtonText = "Yes, activate it!"
                            } else {
                                title = "Are you sure want to deactivate account "+email+"?"
                                type = "warning"
                                confirmButtonClass = "btn-danger"
                                confirmButtonText = "Yes, deactivate it!"
                            }
                            $(this).click(function() {
                                swal({
                                    title: title,
                                    text: "Your will not be able to recover this data!",
                                    type: type,
                                    showCancelButton: true,
                                    confirmButtonClass: confirmButtonClass,
                                    confirmButtonText: confirmButtonText,
                                    closeOnConfirm: false
                                },
                                function(){
                                    $.ajax({
                                        type : "POST",
                                        url : "{{ url('browse/mobile-user/update/status') }}"+"/"+id,
                                        data:{
                                            _token:token,
                                            id:id,
                                            status:action
                                        },
                                        success : function(result) {
                                            if (result.status == "success") {
                                                swal({
                                                    title: 'Updated!',
                                                    text: 'Mobile user account status updated',
                                                    type: 'success',
                                                    showCancelButton: false,
                                                    showConfirmButton: false
                                                })
                                                SweetAlert.init()
                                                window.location.reload(true);
                                            } else if(result.status == "fail"){
                                                swal("Error!", result.messages[0], "error")
                                            } else {
                                                swal("Error!", "Something went wrong. Failed to update status Mobile User.", "error")
                                            }
                                        }
                                    });
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
            $('#per_page').on('change', function () {
                let perPageValue = $(this).val();
                let currentUrl = window.location.href;
                let url = new URL(currentUrl);
                url.searchParams.set('per_page', perPageValue);
                window.location.href = url.toString();
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

    <div class="portlet box">
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-lg-12 col-xs-12 col-sm-12">
                        <div class="col-md-6">
                            <div class="dashboard-stat grey">
                                <div class="visual">
                                    <i class="fa fa-car"></i>
                                </div>
                                <div class="details">
                                    <div class="number">
                                        <span data-counter="counterup" class="bold">{{ $vehicle_count }}</span> </div>
                                    <div class="desc">
                                        Car Types
                                    </div>
                                </div>
                                <span class="more" href=""></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="dashboard-stat grey">
                                <div class="visual">
                                    <i class="fa fa-money"></i>
                                </div>
                                <div class="details">
                                    <div class="number">
                                        <span data-counter="counterup" class="bold">{{ number_format($cas_point, 0, ',', '.') }}</span> </div>
                                    <div class="desc">
                                        Cas Poin
                                    </div>
                                </div>
                                <span class="more" href=""></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-dark sbold uppercase font-blue">{{$sub_title}}</span>
            </div>
        </div>
        <div class="portlet-body m-form__group row">
            <form class="form-horizontal" role="form" action="{{ url()->current() }}" method="post" enctype="multipart/form-data" id="myForm">
                <div class="col-md-12">
                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Status</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-12">
                                        <span class="btn btn-md btn-circle @if ($detail['is_active'] == true) btn-info @elseif ($detail['is_active'] == false) btn-danger @endif">@if ($detail['is_active'] == true) {{ "Active" }} @elseif ($detail['is_active'] == false) {{ "Non-active" }}  @endif</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Name
                                    {{-- <span class="required" aria-required="true">*</span> --}}
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="name" @if (isset($detail['name'])) value="{{ $detail['name'] }}" @else value="{{ old('name') }}" @endif disabled required >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Email
                                    {{-- <span class="required" aria-required="true">*</span> --}}
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="email" @if (isset($detail['email'])) value="{{ $detail['email'] }}" @else value="{{ old('email') }}" @endif disabled required >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Postal Code
                                    {{-- <span class="required" aria-required="true">*</span> --}}
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="postal_code" @if (isset($detail['postal_code'])) value="{{ $detail['postal_code'] }}" @else value="{{ old('postal_code') }}" @endif disabled required >
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
                            @if ($detail['is_active'])
                                <div class="col-md-offset-4 col-md-2">
                                    <a class="btn btn-danger button-action" data-id="{{ $detail['id'] }}" data-email="{{ $detail['email'] }}"  data-action="deactivate"><i class="fa fa-times"></i> Deactivate</a>
                                </div>
                            @else
                                <div class="col-md-offset-4 col-md-2">
                                    <a class="btn btn-info button-action" data-id="{{ $detail['id'] }}" data-email="{{ $detail['email'] }}" data-action="activate"><i class="fa fa-check"></i> Activate</a>
                                </div>
                            @endif
                            <div class="col-md-2">
                                <a href="{{ url('browse/mobile-user') }}" class="btn btn-outline blue"><i class="fa fa-arrow-left"></i> Back</a>
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
                <span class="caption-subject font-blue sbold uppercase">Vehicle List</span>
            </div>
        </div>
        <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                <thead>
                    <tr style="text-align: center">
                        <th> Brand </th>
                        <th> Type </th>
                    </tr>
                </thead>
                <tbody id="transaction">
                    @if (!empty($vehicles))
                        @foreach ($vehicles as $vehicle)
                            <tr>
                                <td>{{ $vehicle['vehicle_brand']['name'] }}</td>
                                <td>{{ $vehicle['vehicle_type']['name'] }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2" style="text-align: center;">No Data Found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue sbold uppercase">Transaction History</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="col-md-3" style="margin-bottom: 10px;">
                <label>
                    <select id="per_page" class="form-control input-sm input-xsmall input-inline">
                        <option value="5" {{ $pagination['per_page'] == 5 ? 'selected' : "" }}>5</option>
                        <option value="10" {{ $pagination['per_page'] == 10 ? 'selected' : "" }}>10</option>
                        <option value="15" {{ $pagination['per_page'] == 15 ? 'selected' : "" }}>15</option>
                        <option value="20" {{ $pagination['per_page'] == 20 ? 'selected' : "" }}>20</option>
                        <option value="50" {{ $pagination['per_page'] == 50 ? 'selected' : "" }}>50</option>
                    </select>
                     entries
                </label>
            </div>
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                <thead>
                    <tr style="text-align: center">
                        <th> Code </th>
                        <th> Time </th>
                        <th> Status </th>
                        <th> Payment </th>
                        <th> Created </th>
                        <th width="100px;"> Action </th>
                    </tr>
                </thead>
                <tbody id="transaction">
                    @if (!empty($transactions))
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction['transaction_code'] }}</td>
                                <td>{{ $transaction['order_time'] }}</td>
                                <td style="text-align: center">
                                    @if ($transaction['transaction_status'] == 'Completed')
                                        <span class="badge badge-success badge-sm">{{ $transaction['transaction_status'] }}</span>
                                    @elseif ($transaction['transaction_status'] == 'Canceled')
                                        <span class="badge badge-danger badge-sm">{{ $transaction['transaction_status'] }}</span>
                                    @elseif ($transaction['transaction_status'] == 'Pending')
                                        <span class="badge badge-warning badge-sm">{{ $transaction['transaction_status'] }}</span>
                                    @elseif ($transaction['transaction_status'] == 'Waiting')
                                        <span class="badge badge-warning badge-sm">{{ $transaction['transaction_status'] }}</span>
                                    @elseif ($transaction['transaction_status'] == 'Paid')
                                        <span class="badge badge-info badge-sm">{{ $transaction['transaction_status'] }}</span>
                                    @else
                                        <span class="badge badge-default badge-sm">{{ $transaction['transaction_status'] }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    @if ($transaction['transaction_payment_status'] == 'Completed')
                                        <span class="badge badge-success badge-sm">{{ $transaction['transaction_payment_status'] }}</span>
                                    @elseif ($transaction['transaction_payment_status'] == 'Canceled')
                                        <span class="badge badge-danger badge-sm">{{ $transaction['transaction_payment_status'] }}</span>
                                    @elseif ($transaction['transaction_payment_status'] == 'Pending')
                                        <span class="badge badge-warning badge-sm">{{ $transaction['transaction_payment_status'] }}</span>
                                    @elseif ($transaction['transaction_payment_status'] == 'Waiting')
                                        <span class="badge badge-warning badge-sm">{{ $transaction['transaction_payment_status'] }}</span>
                                    @elseif ($transaction['transaction_payment_status'] == 'Paid')
                                        <span class="badge badge-info badge-sm">{{ $transaction['transaction_payment_status'] }}</span>
                                    @else
                                        <span class="badge badge-default badge-sm">{{ $transaction['transaction_payment_status'] }}</span>
                                    @endif
                                </td>
                                <td>{{ MyHelper::indonesian_date($transaction['transaction_time']) }}</td>
                                <td style="text-align: center">
                                    <a href="{{ url()->current() }}/transaction/{{ $transaction['id'] }}" class="btn btn-sm blue"><i class="fa fa-search"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                            <tr>
                                <td colspan="8" style="text-align: center;">No Data Found</td>
                            </tr>
                    @endif
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-5 col-sm-5">
                    <div class="dataTables_info" id="table_data_info" role="status" aria-live="polite">
                        Showing {{ (($pagination['current_page']-1)*$pagination['per_page'])+1 }} to {{ $pagination['current_page'] == $pagination['last_page'] ? $pagination['total_data'] : (($pagination['current_page']-1)*$pagination['per_page'])+$pagination['per_page'] }} of {{ $pagination['total_data'] }} entries
                    </div>
                </div>
                <div class="col-md-7 col-sm-7 text-right">
                    <div class="dataTables_paginate paging_bootstrap_number" id="table_data_paginate">
                        <ul class="pagination" style="visibility: visible;">
                            @php
                                $numPages = $pagination['last_page'];
                                $currentPage = $pagination['current_page'];
                                $firstPage = max(min($numPages - 4, $currentPage - 1), 1);
                                $lastPage = min(max(5, $currentPage + 1), $numPages);
                            @endphp

                            @if ($firstPage > 1)
                                <li>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(['per_page' => request()->input('per_page', 10)], ['page' => 1])) }}" alt="First Page"><i class="fa fa-angle-double-left"></i></a>
                                </li>
                                @if ($firstPage > 2)
                                    <li class="disabled"><span>...</span></li>
                                @endif
                            @endif

                            @for ($i = $firstPage; $i <= $lastPage; $i++)
                                <li class="{{ $i == $currentPage ? 'active' : '' }}">
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(['per_page' => request()->input('per_page', 10)], ['page' => $i])) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if ($lastPage < $numPages)
                                @if ($lastPage < $numPages - 1)
                                    <li class="disabled"><span>...</span></li>
                                @endif
                                <li>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(['per_page' => request()->input('per_page', 10)], ['page' => $numPages])) }}" alt="Last Page"><i class="fa fa-angle-double-right"></i></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
