@extends('layouts.main')

@section('page-style')
@endsection

@section('page-script')
@endsection

@section('content')
    <div class="page-bar">
		<ul class="page-breadcrumb">
			<li>
				<a href="{{url('/')}}">Home</a>
			</li>
		</ul>
	</div>
    <h1 class="page-title">
		<i class="fa fa-home font-blue"></i>
		<span class="caption-subject font-blue-sharp sbold">{{$title}}</span>
	</h1>
    <div class="portlet light">
		<div class="portlet-body">
            @include('layouts.notifications')
            Hello<br>
            <b>{{Session::get('user_name')}}</b><br>
			<b>{{Session::get('user_email')}}</b><br><br>
        </div>
    </div>
@endsection
