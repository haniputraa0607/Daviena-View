@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />

    <style>
        .well{
            height: 80px;
        }
    </style>
@endsection

@section('page-plugin')
	<script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/pages/scripts/ui-blockui.min.js') }}" type="text/javascript"></script>
    <script>
        var SweetAlert = function() {
            return {
                init: function() {
                    $(".btn-delete").each(function() {
                        var token  	= "{{ csrf_token() }}";
                        let column 	= $(this).parents('tr');
                        let id     	= $(this).data('id');
                        $(this).click(function() {
                            swal({
                                    title: name+"\n\nAre you sure want to delete this question?",
                                    text: "You will not be able to recover this data!",
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonClass: "btn-danger",
                                    confirmButtonText: "Yes, delete it!",
                                    closeOnConfirm: false
                                },
                                function(){
                                    $.ajax({
                                        type : "GET",
                                        url : "{{ url('setting/faq/delete') }}"+'/'+id,
                                        success : function(result) {
                                            if (result.status == "success") {
                                                swal({
                                                    title: 'Deleted!',
                                                    text: 'The question has been deleted.',
                                                    type: 'success',
                                                    showCancelButton: false,
                                                    showConfirmButton: false
                                                })
                                                SweetAlert.init()
                                                window.location.reload(true);
                                            }
                                            else if(result.status == "fail"){
                                                swal("Error!", result.messages[0], "error")
                                            }
                                            else {
                                                swal("Error!", "Something went wrong. Failed to delete question and answer.", "error")
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
    <script type="text/javascript">
        $(document).ready(function(){
            $('#sortable').sortable();
            $( "#sortable" ).disableSelection();
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
            <div class="m-heading-1 border-green m-bordered">
                <p>Anda dapat mengatur urutan List FAQ dengan cara "Drag and Drop" pada setiap box dibawah ini. <br> Atau menambah item baru dengan tombol berikut : </p>
                <br>
                <a href="{{ url('setting/faq/new') }}" class="btn btn-success btn_add_question">
                    <i class="fa fa-plus"></i> Add New Question</a>
            </div>
        </div>
        <div class="portlet-body form">
            <form class="form-horizontal form-bordered" id="formFaqSort" action="{{ url('setting/faq') }}" method="post">
                <div class="form-body">
                    <div class="form-group">
                        <div class="clearfix" id="sortable" style="padding-top: 10px;">
                            @foreach ($result as $question)
                                <?php
                                    switch ($question['question_type']) {
                                        case 'step_by_step':
                                            $text = "Step by step Answer";
                                            $color = "info";
                                            break;

                                        case 'webview':
                                            $text = "Webview Answer";
                                            $color = "danger";
                                            break;

                                        default:
                                            $text = "Regular Answers";
                                            $color = "dark";
                                            break;
                                    }
                                ?>
                                @if(strlen($question['question']) > 85)
                                    <div class="portlet portlet-sortable light bordered col-md-offset-2 col-md-8">
                                        <div class="portlet-title text-{{ $color }}">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <i class="fa fa-arrows tooltips" data-original-title="Ubah urutan dengan drag n drop" data-container="body"></i>
                                                    <span class="caption-subject bold" style="font-size: 12px !important; padding-left:5px;"> {{ $text }}</span>
                                                </div>
                                                <div class="col-md-offset-4 col-md-1 text-right">
                                                    <a href="{{ url('setting/faq') }}/{{ $question['id'] }}" class="btn blue btn-circle btn-edit"><i class="fa fa-edit"></i> </a>
                                                </div>
                                                <div class="col-md-1 text-right">
                                                    <a class="btn red-mint btn-circle btn-delete" data-id="{{ $question['id'] }}"><i class="fa fa-trash-o"></i> </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <input type="hidden" name="id[]" value="{{ $question['id'] }}">
                                            <h4 class="card-title">{{substr($question['question'],0,85)}} ...</h4>
                                        </div>
                                    </div>
                                @else
                                    <div class="portlet portlet-sortable light bordered col-md-offset-2 col-md-8">
                                        <div class="portlet-title text-{{ $color }}">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <i class="fa fa-arrows tooltips" data-original-title="Ubah urutan dengan drag n drop" data-container="body"></i>
                                                    <span class="caption-subject bold" style="font-size: 12px !important; padding-left:5px;"> {{ $text }}</span>
                                                </div>
                                                <div class="col-md-offset-4 col-md-1 text-right">
                                                    <a href="{{ url('setting/faq') }}/{{ $question['id'] }}" class="btn blue btn-circle btn-edit"><i class="fa fa-edit"></i> </a>
                                                </div>
                                                <div class="col-md-1 text-right">
                                                    <a class="btn red-mint btn-circle btn-delete" data-id="{{ $question['id'] }}"><i class="fa fa-trash-o"></i> </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <input type="hidden" name="id[]" value="{{ $question['id'] }}">
                                            <h4 class="card-title">{{$question['question']}}</h4>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    {{ csrf_field() }}
                    <div class="col-md-offset-4 col-md-4 text-center">
                        <button type="submit" class="btn yellow"><i class="fa fa-save"></i> Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
