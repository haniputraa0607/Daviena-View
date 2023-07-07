@extends('layouts.main-closed')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
	<script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/jquery-repeater/jquery.repeater.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
	<script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/pages/scripts/form-repeater.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <style type="text/css">
        .sort-icon{
         position: absolute;
         top: 7px;
         left: 0px;
         z-index: 10;
         color: #777;
         cursor: move;
        }
    </style>

    <script type="text/javascript">
        $(document).ready(function(){
            /* sortable */
            const repeaterSection = $('.mt-repeater');
            const sortable = $( "#sortable" ).sortable({
                update: function() {
                    repeaterSection.repeater( 'setIndexes' );
                }
            });
            $( "#sortable" ).disableSelection();

            $(".file").change(function(e) {
                var type      = $(this).data('jenis');
                var widthImg  = 1080;
                var heightImg = 1920;
                var id = this.id;

                var _URL = window.URL || window.webkitURL;
                var image, file;

                if ((file = this.files[0])) {
                    var fileExtension = file.name.split('.').pop().toLowerCase();
                    var fileSize = file.size; // File size in bytes
                    var maxSize = 5 * 1024 * 1024; // Maximum file size in bytes (5MB)
                    if (fileSize >= maxSize) {
                        toastr.warning("Your file is too large.");
                        $("#removeImage"+id).trigger( "click" );
                    }

                    if (fileExtension !== 'jpg' && fileExtension !== 'png') {
                        toastr.warning("Only JPG and PNG files are allowed.");
                        $("#removeImage"+id).trigger( "click" );
                    }

                    image = new Image();
                    image.onload = function() {
                        if (this.width != widthImg || this.height != heightImg) {
                            toastr.warning("Please check dimension of your photo.");
                            $("#removeImage"+id).trigger( "click" );
                        }
                    };
                    image.src = _URL.createObjectURL(file);
                }
            });

            var count = {{count($value_text??[])}};
            $( "#modalAddImage" ).on('show.bs.modal', function(e){
                if ($('.btn_add_image').parents(".mt-repeater").find("div[data-repeater-item]").length >= $('.btn_add_image').parents(".mt-repeater").attr("data-limit")) {
                    alert("List tutorial maksimal hanya " + $('.btn_add_image').parents(".mt-repeater").attr("data-limit") + ' gambar');
                    return e.preventDefault();
                }
            });
        });
    </script>

    <script type="text/javascript">
        var SweetAlert = function() {
            return {
                init: function() {
                    $(".action-delete").each(function() {
                        let column 	= $(this).closest('.col-md-12').find("input[type='hidden']");
                        $(this).click(function() {
                            if ($('.mt-repeater-item').length > 1) {
                                swal({
                                    title: "Are you sure want to delete this image?",
                                    text: "Your will not be able to recover this data!",
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonClass: "btn-danger",
                                    confirmButtonText: "Yes, delete it!",
                                    closeOnConfirm: false
                                },
                                function(){
                                    column.remove();
                                    $('form#onboarding_form').submit();
                                });
                            } else {
                                swal({
                                    title: "Image cannot be empty!",
                                    text: "There must be at least 1 image",
                                    type: "error",
                                    confirmButtonClass: "btn-info",
                                    confirmButtonText: "Ok",
                                    closeOnConfirm: true
                                });
                            }
                        })
                    })
                }
            }
        }();

        jQuery(document).ready(function() {
            SweetAlert.init()
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
                <span class="caption-subject font-dark sbold uppercase">{{$sub_title}}</span>
            </div>
        </div>
        <div class="portlet-body form">
            <form class="form-horizontal form-bordered" id="onboarding_form" action="{{ url('setting/on-boarding') }}" method="post" enctype="multipart/form-data">
                <div class="form-body">
                    <div class="form-group">
                        <label class="control-label col-md-3">
                                Status Active
                            <i class="fa fa-question-circle tooltips" data-original-title="Status tutorial aplikasi, jika Active maka gambar tutorial akan tampil di apps dan jika Inactive maka gambar tutorial tidak akan tampil di apps" data-container="body"></i>
                        </label>
                        <div class="col-md-9">
                            <input type="checkbox" name="active" @if(isset($setting['active']) && $setting['active'] == '1') checked @endif class="make-switch switch-change" data-size="small" data-on-text="Active" data-off-text="Inactive">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">
                                Status Skipable
                            <i class="fa fa-question-circle tooltips" data-original-title="Status dilewati tutorial, jika Active maka gambar tutorial dapat dilewati oleh pengguna" data-container="body"></i>
                        </label>
                        <div class="col-md-9">
                            <input type="checkbox" name="skipable" @if(isset($setting['skipable']) && $setting['skipable'] == '1') checked @endif class="make-switch switch-change" data-size="small" data-on-text="Active" data-off-text="Inactive">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">
                                Text Button <i class="fa fa-question-circle tooltips" data-original-title="Teks untuk button yang akan ditampilkan pada halaman tutorial" data-container="body"></i>
                        </label>
                        <div class="col-md-9">
                            <div class="col-md-6">
                                <div class="form-group" style="border: none;">
                                    <img class="img-responsive" src="{{env('STORAGE_URL_VIEW').'images/setting/next.jpg'}}" alt="">
                                    <div class="input-group" style="border: none;border: none;width: 60%;margin: auto;">
                                        <input maxlength="11" type="text" name="text_next" @if(isset($setting['text_next'])) value="{{$setting['text_next']}}" @endif class="form-control" required>
                                        <span class="input-group-addon">
                                            <i style="color:#333;" class="fa fa-question-circle tooltips" data-original-title="Input ini akan menggantikan text Selanjutnya (Default), di tampilkan di bagian bawah kanan" data-container="body"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="border: none;">
                                    <img class="img-responsive" src="{{env('STORAGE_URL_VIEW').'images/setting/skip.jpg'}}" alt="">
                                    <div class="input-group" style="border: none;border: none;width: 60%;margin: auto;">
                                        <input maxlength="11" type="text" name="text_skip" @if(isset($setting['text_skip'])) value="{{$setting['text_skip']}}" @endif class="form-control" required>
                                        <span class="input-group-addon">
                                            <i style="color:#333;" class="fa fa-question-circle tooltips" data-original-title="Input ini akan menggantikan text Lewati (Default), di tampilkan di bagian bawah kiri" data-container="body"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="border: none;">
                                    <img class="img-responsive" src="{{env('STORAGE_URL_VIEW').'images/setting/last_button.jpg'}}" alt="">
                                    <div class="input-group" style="border: none;border: none;width: 60%;margin: auto;">
                                        <input maxlength="11" type="text" name="text_last" @if(isset($setting['text_last'])) value="{{$setting['text_last']}}" @endif class="form-control" required>
                                        <span class="input-group-addon">
                                            <i style="color:#333;" class="fa fa-question-circle tooltips" data-original-title="Input ini akan menggantikan text Mulai (Default), menggantikan text Next (Default) jika sudah di gambar terakhir" data-container="body"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">
                            Intro List <i class="fa fa-question-circle tooltips" data-original-title="List gambar sesuai urutan" data-container="body"></i>
                        </label>
                        <div class="col-md-9">
                            <div class="col-md-12">
                                <div class="mt-repeater" data-limit="8">
                                    <div data-repeater-list="image" id="sortable">
                                        @if (isset($setting['image']) && $setting['image'] != null)
                                            @foreach ($setting['image'] as $key=>$item)
                                                <div data-repeater-item class="mt-repeater-item mt-overflow" style="border-bottom: 1px #ddd;">
                                                    <div class="mt-repeater-cell" style="position: relative;">
                                                        <div class="sort-icon">
                                                            <i class="fa fa-arrows tooltips" data-original-title="Ubah urutan form dengan drag n drop" data-container="body"></i>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="col-md-2">
                                                                <a class="btn btn-danger mt-repeater-delete mt-repeater-del-right mt-repeater-btn-inline action-delete">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </div>
                                                            <div class="input-icon right">
                                                                <label class="col-md-4 control-label">
                                                                    Image Tutorial <span class="required" aria-required="true"> * </span>
                                                                    {{-- <br>
                                                                    <span class="required" aria-required="true"> (1080*1920) </span> <i class="fa fa-question-circle tooltips" data-original-title="Gambar dengan ukuran portrait ditampilkan pada halaman awal aplikasi mobile" data-container="body"></i> --}}
                                                                </label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail" style="width: 150px;">
                                                                        <img class='previewImage' src="{{env('STORAGE_URL_API') . $item['path']}}" alt="">
                                                                        <input type="hidden" name="id" value="{{$item['id']}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div data-repeater-item class="mt-repeater-item mt-overflow" style="border-bottom: 1px #ddd;">
                                                <div class="mt-repeater-cell" style="position: relative;">
                                                    <div class="sort-icon">
                                                        <i class="fa fa-arrows tooltips" data-original-title="Ubah urutan form dengan drag n drop" data-container="body"></i>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="col-md-2">
                                                        </div>
                                                        <div class="input-icon right">
                                                            <label class="col-md-4 control-label">
                                                            Image Tutorial
                                                            {{-- <span class="required" aria-required="true"> * </span>
                                                            <br>
                                                            <span class="required" aria-required="true"> (1080*1920) </span>
                                                            <i class="fa fa-question-circle tooltips" data-original-title="Gambar dengan ukuran portrait ditampilkan pada halaman awal aplikasi mobile" data-container="body"></i> --}}
                                                            </label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail" style="width: 150px;">
                                                                    <img class='previewImage' src="https://via.placeholder.com/1080x1920?text=No+Image" alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-action col-md-12">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-10">
                                            <a href="#modalAddImage" class="btn btn-success btn_add_image" data-toggle="modal">
                                            <i class="fa fa-plus"></i> Add New Image</a>
                                        </div>
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
                            <div class="row">
                                <div class="col-md-offset-4 col-md-4 text-center">
                                    <button type="submit" class="btn yellow"><i class="fa fa-save"></i> Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modalAddImage" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add On-Boarding Image</h4>
                </div>
                <div class="modal-body form">
                    <div class="form-body">
                    <form role="form" id="update-featured-songwriter" action="{{ url('setting/on-boarding/add') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row" style="padding: 10px">
                            <div class="col-md-3">
                                <div class="input-icon right">
                                    <label class="col-md-4 control-label">Image<span class="required" aria-required="true">*</span>
                                    <br>
                                    <span class="required" aria-required="true"> (1080*1920) </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 100%;">
                                        <img class='previewImage' src="https://via.placeholder.com/1080x1920?text=No+Image" alt="">
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" id="image_landscape"  style="width: 100%;"></div>
                                    <div class='btnImage'>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"> Select image </span>
                                            <span class="fileinput-exists"> Change </span>
                                            <input type="hidden" name="order" value="{{ count($setting['image']) + 1 }}">
                                            <input type="file" id="modalNew" accept="image/*" value="" class="file form-control demo featureImageForm" name="file" data-jenis="landscape" required>
                                        </span>
                                        <a href="javascript:;" id="removeImagemodalNew" class="btn red btremove" data-dismiss="fileinput" style="display: none"> Remove </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <center>
                                <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            </center>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
