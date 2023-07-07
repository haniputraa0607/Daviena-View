@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var table=$('#table_data').DataTable({
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
                columns: [
                    null,
                    null,
                    null,
                    null,
                    { orderable: false }
                ],
                lengthMenu: [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"]
                ],
                pageLength: 10
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

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue sbold uppercase">ECS List</span>
            </div>
        </div>
        <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                <thead>
                    <tr style="text-align: center">
                        <th> ECS Code </th>
                        <th> Location </th>
                        <th> Connector </th>
                        <th> Status </th>
                        <th> Action </th>
                    </tr>
                </thead>
                <tbody id="brand">
                    @if (!empty($devices))
                        @foreach ($devices as $device)
                            <tr style="text-align: center">
                                <td>{{ $device['ecs_id'] }}</td>
                                <td>{{ $device['location_name'] }}</td>
                                <td>{{ $device['num_of_connector'] }}</td>
                                <td>
                                    @if ($device['status'] == 'NEW')
                                        <span class="badge badge-success">New</span>
                                    @elseif ($device['status'] == 'ACTIVE')
                                        <span class="badge badge-primary">Active</span>
                                    @else
                                        <span class="badge badge-default">Inactive</span>
                                    @endif
                                </td>
                                <td style="width: 90px;">
                                    <a href="{{ url('browse/ecs') }}/{{ $device['id'] }}" class="btn btn-sm blue"><i class="fa fa-search"></i></a>
                                </td>
                            </tr>
                        @endforeach

                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
