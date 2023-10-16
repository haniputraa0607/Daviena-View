@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/css/select2.min.css' }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/css/select2-bootstrap.min.css' }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/js/select2.min.js' }}" type="text/javascript"></script>
    <script type="text/javascript">
        var tableOrder = $('#table_data').DataTable({
            bProcessing: true,
            bServerSide: true,
            ajax: {
                url: "api/be/order/list",
                headers: {
                    "Authorization": "{{ session('access_token') }}"
                },
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className:"text-center"},
                { data: 'order_code', name: 'order_code' },
                { data: 'order_date', name: 'order_date' },
                { data: 'status', name: 'status' },
                { data: 'order_grandtotal', name: 'order_grandtotal' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className:"text-center"},
            ],
            language: {
                aria: {
                    sortAscending: ": activate to sort column ascending",
                    sortDescending: ": activate to sort column descending"
                },
                emptyTable: "No data available in table",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No entries found",
                infoFiltered: "(filtered1 from _MAX_ total entries)",
                lengthMenu: "_MENU_ entries",
                search: "Search:",
                zeroRecords: "No matching records found"
            },
            responsive: {
                details: {
                    type: "column",
                    target: "tr"
                }
            },
            order: [],
            lengthMenu: [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"]
            ],
            pageLength: 10
        });
            
        $('#outlet_id').select2({
            placeholder: 'Select Outlet',
            theme: 'bootstrap',
            width: '100%',
            ajax: {
                url: `api/be/outlet/name-id`,
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

        const mainOrder = {
             search: function(){
                let outlet_id = $('#outlet_id').val();
                let start = $('#start_date').val();
                let end = $('#end_date').val();
                let url = `api/be/order/list?start_date=${start}&end_date=${end}&outlet_id=${outlet_id}`;
                tableOrder.ajax.url(url).load();
             }
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
                <span class="caption-subject font-blue sbold uppercase">Order</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-sm-4">
                    <label>Start</label>
                    <input type="date" name="start" id="start_date" class="form-control" value="{{ date('Y-m-d', strtotime('-7 days', strtotime(date('Y-m-d')))); }}">
                </div>
                <div class="col-sm-4">
                    <label>End</label>
                    <input type="date" name="end" id="end_date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Outlet</label>
                        <select class="form-control" name="outlet_id" id="outlet_id">
                            <option value="">All Outlet</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <button type="button" style="margin-top:25px;" class="btn btn-primary" type="button" onclick="mainOrder.search()"><li class="fa fa-search"></li> Search</button>
                    </div>
                </div>
            </div>
            <hr>
            <br>
            <table class="table trace trace-as-text table-striped table-bordered table-hover dt-responsive" id="table_data">
                <thead>
                    <tr style="text-align: center">
                        <th style="text-align: center">No</th>
                        <th style="text-align: center"> Order Code </th>
                        <th style="text-align: center"> Order Date </th>
                        <th style="text-align: center"> Status </th>
                        <th style="text-align: center"> Total </th>
                        <th style="text-align: center"> Action </th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>

@endsection
