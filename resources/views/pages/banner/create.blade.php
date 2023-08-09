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
    <script src="https://cdn.tiny.cloud/1/oowsi03408mi3se06e6g73ocmflkdn4blz5jffod9wz1lc1t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
      });
      
      function readURL(input, level) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          var fileimport = $('#' + input.id).val();
          var allowedExtensions = /(\.png|\.jpg|\.jpeg)$/i;
          if (!allowedExtensions.exec(fileimport)) {
            alert('Gambar harus bertipe gambar');
            $('#' + input.id).val('');
            return false;
          }
          reader.onload = function(e) {
            $('#blah_' + level).attr('src', e.target.result).width(200);
            // .height();
          };
          reader.readAsDataURL(input.files[0]);
        }
      }

      function imgError(data) {
        console.log('error_img');
        data.setAttribute('src', '{{ asset("images/logo.svg") }}');
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
                <a href="{{ url('custom-page') }}">{{ $sub_title }}</a>
            </li>
            @endif
        </ul>
    </div><br>

    @include('layouts.notifications')

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue sbold uppercase ">New Article</span>
            </div>
        </div>
        <div class="portlet-body m-form__group row">
            <form class="form-horizontal" role="form" action="{{ route('article.store') }}"  method="post" enctype="multipart/form-data" id="myForm">
                <div class="col-md-12">
                    <div class="form-body">

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Title<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Title" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="title" placeholder="Title" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Writer<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Writer" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="writer" placeholder="Writer" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="type">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Release Date<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Release Date" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="date" name="release_date"class="form-control" placeholder="Release Date" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Description<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Description" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <textarea type="text" class="form-control" name="description" placeholder="Description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Article Image<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Article Image" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <div class="alert alert-success text-center col-sm-12">
                                            <img id="blah_image" src="{{ @$detail['image'] ? asset('images/'.$detail['image']) : asset('images/logo.svg') }}" style="width:200px;" onerror="imgError(this)" alt="..." loading="lazy">
                                        </div>
                                        <input class="form-control" name="image" style="display:none;" id="image" type="file" onchange="readURL(this, 'image');">
                                        <button class="btn btn-outline-success btn-sm" type="button" onclick="$('#image').click();">Upload</button>
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
                                <button type="submit" class="btn green"><i class="fa fa-check"></i> Subsmit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
