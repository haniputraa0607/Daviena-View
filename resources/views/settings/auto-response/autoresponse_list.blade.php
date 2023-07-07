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

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue sbold uppercase">Auto-Response List</span>
            </div>
        </div>
        <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_data">
                <thead>
                    <tr style="text-align: center">
                        <th> Name </th>
                        <th> Type </th>
                        <th> Email </th>
                        <th> Push Notification </th>
                        <th> Forward </th>
                        <th> Action </th>
                    </tr>
                </thead>
                <tbody id="brand">
                    @if (!empty($auto_responses))
                        @foreach ($auto_responses as $response)
                            <tr>
                                <td>{{ $response['name'] }}</td>
                                <td>{{ $response['crm_type'] }}</td>
                                <td style="text-align: center">
                                    @if ($response['email_toggle'])
                                        <span class="badge badge-success">Enabled</span>
                                    @else
                                        <span class="badge badge-dark">Disabled</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    @if ($response['push_toggle'])
                                        <span class="badge badge-success">Enabled</span>
                                    @else
                                        <span class="badge badge-dark">Disabled</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    @if ($response['forward_toggle'])
                                        <span class="badge badge-success">Enabled</span>
                                    @else
                                        <span class="badge badge-dark">Disabled</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <a href="{{ url('setting/autoresponse') }}/{{ $response['code'] }}" class="btn btn-sm blue"><i class="fa fa-search"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
