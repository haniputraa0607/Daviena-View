@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/datemultiselect/jquery-ui.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script>
        $('.selectpicker').selectpicker();
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
                <span class="caption-subject font-blue sbold uppercase ">New Partner</span>
            </div>
        </div>
        <div class="portlet-body m-form__group row">
            <form class="form-horizontal" role="form" action="{{ route('partner_equal.store') }}"  method="post" enctype="multipart/form-data" id="myForm">
                <div class="col-md-12">
                    <div class="form-body">
                        <div class="col-12">
                            <div class="portlet-title mb-2">
                                <h4><b>Partner</b></h4>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">Name<span class="required" aria-required="true">*</span>
                                            <i class="fa fa-question-circle tooltips" data-original-title="Name" data-container="body"></i>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="name" placeholder="Name" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">Email<span class="required" aria-required="true">*</span>
                                            <i class="fa fa-question-circle tooltips" data-original-title="Email" data-container="body"></i>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="col-md-10">
                                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="type">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">Phone<span class="required" aria-required="true">*</span>
                                            <i class="fa fa-question-circle tooltips" data-original-title="Phone" data-container="body"></i>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="col-md-10">
                                            <input type="text" name="phone" class="form-control" placeholder="Phone" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-12 mt-2">
                            <div class="portlet-title mb-2">
                                <h4><b>Store</b></h4>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">Store Name<span class="required" aria-required="true">*</span>
                                            <i class="fa fa-question-circle tooltips" data-original-title="Store Name" data-container="body"></i>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="store_name" placeholder="Store Name" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">Store Address<span class="required" aria-required="true">*</span>
                                            <i class="fa fa-question-circle tooltips" data-original-title="Store Address" data-container="body"></i>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="store_address" placeholder="Store Address" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="type">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">Store City<span class="required" aria-required="true">*</span>
                                            <i class="fa fa-question-circle tooltips" data-original-title="Store City" data-container="body"></i>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="col-md-10">
                                            <input type="text" name="store_city" class="form-control" placeholder="Store City" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-12 mt-2">
                            <div class="portlet-title mb-2">
                                <h4><b>Sosial Media</b></h4>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">Instagram<span class="required" aria-required="true">*</span>
                                            <i class="fa fa-question-circle tooltips" data-original-title="Instagram" data-container="body"></i>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="url_instagram" placeholder="URL Instagram" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">Tiktok<span class="required" aria-required="true">*</span>
                                            <i class="fa fa-question-circle tooltips" data-original-title="Tiktok" data-container="body"></i>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="url_tiktok" placeholder="URL Tiktok" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">Tokopedia<span class="required" aria-required="true">*</span>
                                            <i class="fa fa-question-circle tooltips" data-original-title="Tokopedia" data-container="body"></i>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="url_tokopedia" placeholder="URL Tokopedia" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">Shopee<span class="required" aria-required="true">*</span>
                                            <i class="fa fa-question-circle tooltips" data-original-title="Shopee" data-container="body"></i>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="url_shopee" placeholder="URL Shopee" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">Buka Lapak<span class="required" aria-required="true">*</span>
                                            <i class="fa fa-question-circle tooltips" data-original-title="Buka Lapak" data-container="body"></i>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="url_buka_lapak" placeholder="URL Buka Lapak" required>
                                        </div>
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
                                <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
