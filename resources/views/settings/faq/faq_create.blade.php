@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-summernote/summernote.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-summernote/summernote.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            var SweetAlert = function() {
                return {
                    init: function() {
                        swal({
                            title: "Step cannot be empty!",
                            text: "There must be at least 1 step in answer",
                            type: "error",
                            confirmButtonClass: "btn-info",
                            confirmButtonText: "Ok",
                            closeOnConfirm: true
                        });
                    }
                }
            }();
            var SweetAlertMax = function() {
                return {
                    init: function() {
                        swal({
                            title: "Cannot add more step",
                            text: "Maximum 20 step!",
                            type: "error",
                            confirmButtonClass: "btn-info",
                            confirmButtonText: "Ok",
                            closeOnConfirm: true
                        });
                    }
                }
            }();
            $( ".sortable" ).sortable();
            $( ".sortable" ).disableSelection();
            $('.summernote').summernote({
                placeholder: 'Frequently Asked Question',
                tabsize: 2,
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['table']],
                    // ['insert', ['link', 'picture', 'video']],
                    ['misc', ['fullscreen', 'codeview', 'help']], ['height', ['height']]
                ],
            });

            $("#answer_type").change(function(e) {
                e.preventDefault();
                var value = $(this).val();
                var new_sortable_item = '<div class="portlet portlet-sortable light bordered col-md-12">'+
                                    '<div class="portlet-body">'+
                                        '<div class="col-md-1 text-center">'+
                                            '<i class="fa fa-arrows tooltips" data-original-title="Ubah urutan dengan drag n drop" data-container="body"></i>'+
                                        '</div>'+
                                        '<div class="col-md-4 text-center">'+
                                            '<div class="fileinput fileinput-new" data-provides="fileinput">'+
                                                '<div class="fileinput-new thumbnail" style="width: 150px;">'+
                                                    '<img class="previewImage" src="https://via.placeholder.com/150/EFEFEF?text=No+Image" alt="">'+
                                                '</div>'+
                                                '<div class="fileinput-preview fileinput-exists thumbnail" id="image_landscape" style="width: 150px;"></div>'+
                                                '<div class="btnImage">'+
                                                    '<span class="btn default btn-file">'+
                                                    '<span class="fileinput-new"> Select image </span>'+
                                                    '<span class="fileinput-exists"> Change </span>'+
                                                    '<input type="file" id="0" accept="image/*" class="file form-control demo featureImageForm" name="file[]" required>'+
                                                    '</span>'+
                                                    '<a href="javascript:;" id="removeImage0" class="btn red fileinput-exists btremove" data-dismiss="fileinput"> Remove </a>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-md-7">'+
                                            '<div class="col-md-12 text-right" style="padding-bottom:15px;">'+
                                                '<a class="btn red-mint btn-circle btn-delete"><i class="fa fa-trash-o"></i></a>'+
                                            '</div>'+
                                            '<div class="col-md-12" style="padding-bottom:15px;">'+
                                                '<input type="text" name="answer_title[]" class="form-control" placeholder="Title" required/>'+
                                            '</div>'+
                                            '<div class="col-md-12">'+
                                                '<textarea name="answer_description[]" class="form-control" rows="2" placeholder="Description" required></textarea>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                $('.answer-field').empty();

                if (value == 'step_by_step') {
                    $('.answer-field').append('<input type="text" name="answer_header" class="form-control" value="" placeholder="Answer Header Text" required/>');
                    $('.answer-field').append('<div class="form-action col-md-12" style="padding-bottom:15px;padding-top:15px;"><div class="row"><a class="btn btn-success btn_add_step" data-toggle="modal"><i class="fa fa-plus"></i> Add New Step</a></div></div>');
                    $('.answer-field').append('<div class="sortable"></div>');
                    $('.sortable').append(new_sortable_item);
                    $('.btn-delete').on('click', function() {
                        if ($('.portlet-sortable').length > 1) {
                            $(this).closest('.portlet-sortable').remove();
                        } else {
                            SweetAlert.init()
                        }
                    });

                    $('.btn_add_step').on('click', function(event) {
                        if ($('.portlet-sortable').length > 19) {
                            SweetAlertMax.init()
                        } else {
                            $('.sortable').append(new_sortable_item);
                            $('.btn-delete').on('click', function() {
                                if ($('.portlet-sortable').length > 1) {
                                    $(this).closest('.portlet-sortable').remove();
                                } else {
                                    SweetAlert.init()
                                }
                            });
                        }
                    });
                    $('.sortable').sortable();
                } else if (value == 'webview') {
                    $('.answer-field').append('<input type="text" name="answer" class="form-control" placeholder="URL Webview" required/>')
                } else {
                    $('.answer-field').append('<textarea name="answer" class="form-control summernote" rows="10"></textarea>')
                    $('.summernote').summernote({
                        placeholder: 'Frequently Asked Question',
                        tabsize: 2,
                        height: 300,
                        toolbar: [
                            ['style', ['style']],
                            ['style', ['bold', 'italic', 'underline', 'clear']],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['insert', ['table']],
                            // ['insert', ['link', 'picture', 'video']],
                            ['misc', ['fullscreen', 'codeview', 'help']], ['height', ['height']]
                        ],
                    });
                }
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

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue sbold uppercase">Create FAQ</span>
            </div>
        </div>
        <div class="portlet-body form">
            <form class="form-horizontal form-bordered" action="{{ url('setting/faq/new') }}" method="post" enctype="multipart/form-data">
                <div class="form-body">
                    <div class="form-group">
                        <label class="control-label col-md-3">
                                Question<span class="required" aria-required="true">*</span>
                            <i class="fa fa-question-circle tooltips" data-original-title="pertanyaan yang sering diajukan" data-container="body"></i>
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="question" class="form-control" placeholder="Question" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">
                                Header Text<span class="required" aria-required="true">*</span>
                            <i class="fa fa-question-circle tooltips" data-original-title="Header tampilan jawaban pertanyaan" data-container="body"></i>
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="header" class="form-control" placeholder="Header Text" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">
                                Answer Type<span class="required" aria-required="true">*</span>
                            <i class="fa fa-question-circle tooltips" data-original-title="tipe jawaban FAQ" data-container="body"></i>
                        </label>
                        <div class="col-md-9">
                            <select name="type" class="form-control" id="answer_type">
                                <option value="regular">Regular</option>
                                <option value="step_by_step">Step by Step</option>
                                <option value="webview">Web View</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">
                                Answer<span class="required" aria-required="true">*</span>
                            <i class="fa fa-question-circle tooltips" data-original-title="jawaban" data-container="body"></i>
                        </label>
                        <div class="col-md-9 answer-field">
                            <textarea name="answer" class="form-control summernote" rows="10"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-offset-4 col-md-4 text-center">
                                    <button type="submit" class="btn green">
                                        <i class="fa fa-check"></i> Submit</button>
                                    <a href="{{ url('setting/faq') }}"><button type="button" class="btn default">Cancel</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
