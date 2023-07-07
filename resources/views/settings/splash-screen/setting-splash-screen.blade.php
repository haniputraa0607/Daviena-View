@extends('layouts.main')

@section('page-style')
	<link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('page-plugin')
	<script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
	<script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js') }}" type="text/javascript"></script>
	<script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
@endsection

@section('page-script')
    <script>
        $(".file-splash").change(function(e) {
            var width  = 1080;
            var height = 1920;
            var width2  = 540;
            var height2 = 960;

            var _URL = window.URL || window.webkitURL;
            var file = this.files[0];
            var fileType = file.type;
            var reader  = new FileReader();

            if (fileType === 'image/jpeg' || fileType === 'image/png' || fileType === 'video/mp4') {
                var media = document.createElement(fileType === 'video/mp4' ? 'video' : 'img');

                if (fileType === 'video/mp4') {
                    reader.addEventListener("load", function () {
                        var dataUrl =  reader.result;
                        var $videoEl = $('<video style="width:100%; height:100%;" controls></video>');
                        var $failedVideo = $('<img src="https://via.placeholder.com/540x960?text=Invalid+Dimension" style="width:100%; height:100%;">');
                        $('#div_splash').empty().append($videoEl);
                        $videoEl.attr('src', dataUrl);

                        var videoTagRef = $videoEl[0];
                        videoTagRef.addEventListener('loadedmetadata', function(e){
                            if ((videoTagRef.videoWidth == width && videoTagRef.videoHeight == height) || (videoTagRef.videoWidth == width2 && videoTagRef.videoHeight == height2)) {
                                // do nothing for valid dimension
                            } else {
                                toastr.warning("Please check dimension of your video.");

                                $('#field_splash').val("");
                                $('#div_splash').empty().append($failedVideo);
                            }
                        });

                    }, false);

                    if (file) {
                    reader.readAsDataURL(file);
                    }

                } else {
                    media.onload = function() {
                        if ((this.width == width && this.height == height) || (this.width == width2 && this.height == height2)) {
                            // do nothing for valid dimension
                        } else {
                            toastr.warning("Please check dimension of your image.");
                            $(this).val("");

                            $('#field_splash').val("");
                            $('#div_splash').children('img').attr('src', 'https://via.placeholder.com/540x960?text=Invalid+Dimension');
                        }
                    };

                    media.src = _URL.createObjectURL(file);
                }

            } else {
                toastr.warning("Please upload a valid image or video file.");
                $(this).val("");
            }
        });

    </script>
@endsection

@section('content')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="javascript:;">Setting</a>
            </li>
        </ul>
    </div>
    <br>
    @include('layouts.notifications')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-blue ">
                <i class="icon-settings font-blue "></i>
                <span class="caption-subject bold uppercase">Default Splash Screen</span>
            </div>
        </div>
        <div class="portlet-body">
            <form role="form" class="form-horizontal" action="{{url('setting/splash-screen')}}" method="POST" enctype="multipart/form-data">
                <div class="form-body">
                    <div class="form-group col-md-12">
                        <label class="text-right col-md-4">Splash Screen Duration
                            <span class="required" aria-required="true"> * </span>
                            <i class="fa fa-question-circle tooltips" data-original-title="Durasi dalam satuan detik" data-container="body"></i>
                        </label>
                        <div class="col-md-4">
                            <input type="number" class="form-control" name="default_home_splash_duration" value="{{$default_home['default_home_splash_duration']??''}}" min="1">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="text-right col-md-4">Splash Screen Signature Key
                            <span class="required" aria-required="true"> * </span>
                            <i class="fa fa-question-circle tooltips" data-original-title="Signature key untuk media splash screen (berganti setiap ada penggantian media splash screen)" data-container="body"></i>
                        </label>
                        <div class="col-md-4">
                            <textarea class="form-control" rows="4" style="resize:none; overflow:hidden" readonly>{{$default_home['signature_key']??''}}</textarea>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                            <label class="control-label col-md-4">Splash Screen
                                <span class="required" aria-required="true"> * </span>
                                <i class="fa fa-question-circle tooltips" data-original-title="Media yang ditampilkan  splash screen berupa gambar atau video (.mp4)" data-container="body"></i>
                                <br>
                                <span class="required" aria-required="true"> (1080x1920)/(540x960) </span>
                            </label><br>
                            <div class="fileinput fileinput-new col-md-4" data-provides="fileinput">
                                <div class="fileinput-new thumbnail">
                                    @if(isset($default_home['default_home_splash_screen']))
                                        @if(strpos($default_home['default_home_splash_screen'], 'mp4') !== false)
                                            <video id="splash_video" src="{{ env('STORAGE_URL_API')}}{{$default_home['default_home_splash_screen']}}?updated_at={{time()}}" style="width:100%; height:100%;" controls></video>
                                        @else
                                            <img id="splash_image" src="{{ env('STORAGE_URL_API')}}{{$default_home['default_home_splash_screen']}}?updated_at={{time()}}" alt="">
                                        @endif
                                    @else
                                        <img src="https://via.placeholder.com/540x960?text=No+Image" alt="">
                                    @endif
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" id="div_splash" style="max-width: 270px; max-height: 480px;"></div>
                                <div>
                                    <span class="btn default btn-file">
                                    <span class="fileinput-new"> Select </span>
                                    <span class="fileinput-exists"> Change </span>
                                    <input type="file" class="file file-splash" id="field_splash" accept="image/*, .mp4" name="default_home_splash_screen">
                                    </span>
                                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="form-actions" style="text-align:center">
                    {{ csrf_field() }}
                    <button type="submit" class="btn yellow"><i class="fa fa-save"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
