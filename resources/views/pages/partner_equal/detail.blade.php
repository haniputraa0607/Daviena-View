@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/datemultiselect/jquery-ui.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
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
                <span>{{ $sub_title }}</span>
            </li>
            @endif
        </ul>
    </div><br>

    @include('layouts.notifications')

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue bold uppercase">Detail Partner</span>
            </div>
        </div>
        <div class="portlet-body m-form__group row">
            <form class="form-horizontal" role="form" action="{{ route('partner_equal.update', $detail['partner']['id']) }}"  method="post" enctype="multipart/form-data" id="myForm">
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
                                            <input type="text" class="form-control" name="name" placeholder="Name" required value="{{ old('name') ? old('name') : ($detail['partner'] ? $detail['partner']['name'] : '') }}">
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
                                            <input type="email" class="form-control" name="email" placeholder="Email" required value="{{ old('email') ? old('email') : ($detail['partner'] ? $detail['partner']['email'] : '') }}">
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
                                            <input type="text" name="phone" class="form-control" placeholder="Phone" value="{{ old('phone') ? old('phone') : ($detail['partner'] ? $detail['partner']['phone'] : '') }}">
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
                                            <input type="text" class="form-control" name="store_name" placeholder="Store Name" required value="{{ old('store_name') ? old('store_name') : ($detail['store'] ? $detail['store']['store_name'] : '') }}">
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
                                            <textarea class="form-control" name="store_address" placeholder="Store Address" required>{{ old('store_address') ? old('store_address') : ($detail['store'] ? $detail['store']['store_address'] : '') }}</textarea>
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
                                            <input type="text" name="store_city" class="form-control" placeholder="Store City" required value="{{ old('store_city') ? old('store_city') : ($detail['store'] ? $detail['store']['store_city'] : '') }}">
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
                                            <input type="text" class="form-control" name="url_instagram" placeholder="URL Instagram" required value="{{ old('url_instagram') ? old('url_instagram') : (array_key_exists('url', $sosial_media['instagram']) ? $sosial_media['instagram']['url'] : '') }}">
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
                                            <input type="text" class="form-control" name="url_tiktok" placeholder="URL Tiktok" required value="{{ old('url_tiktok') ? old('url_tiktok') : (array_key_exists('url', $sosial_media['tiktok']) ? $sosial_media['tiktok']['url'] : '') }}">
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
                                            <input type="text" class="form-control" name="url_tokopedia" placeholder="URL Tokopedia" required value="{{ old('url_tokopedia') ? old('url_tokopedia') : (array_key_exists('url', $sosial_media['tokopedia']) ? $sosial_media['tokopedia']['url'] : '') }}">
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
                                            <input type="text" class="form-control" name="url_shopee" placeholder="URL Shopee" required value="{{ old('url_shopee') ? old('url_shopee') : (array_key_exists('url', $sosial_media['shopee']) ? $sosial_media['shopee']['url'] : '') }}">
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
                                            <input type="text" class="form-control" name="url_buka_lapak" placeholder="URL Buka Lapak" required value="{{ old('url_buka_lapak') ? old('url_buka_lapak') : (array_key_exists('url', $sosial_media['bukalapak']) ? $sosial_media['bukalapak']['url'] : '') }}">
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
