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
          var allowedExtensions = /(\.png)$/i;
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
                <span>Landing Page</span>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <strong>Official Partner</strong>
            </li>
        </ul>
    </div><br>

    @include('layouts.notifications')

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue bold uppercase">Consultation Ordering</span>
            </div>
        </div>

        <div class="portlet-body m-form__group row">
            <form class="form-horizontal" role="form" action="{{ route('landing_page.consultation_ordering.update') }}"  method="post" enctype="multipart/form-data" id="myForm">
                <div class="col-md-12">
                    <div class="form-body">
                        @php
                            $official_value = json_decode($detail['official_value']);
                        @endphp

                        <input type="hidden" name="id" value="{{ $detail['id'] ?? '' }}">
                        @foreach($official_value[0]->contact as $key)
                            <strong>Contact {{ $loop->index+1 }}</strong>
                            <div class="form-group">
                                <div class="form-group p-2 col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">Name<span class="required" aria-required="true">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="name[]" placeholder="Name" required value="{{ $key->name }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group p-2 col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">Telp<span class="required" aria-required="true">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="telp[]" placeholder="Telp" required value="{{ $key->telp }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <strong>Service Hours</strong>
                        <div class="form-group">
                            <div class="form-group p-2 col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Time<span class="required" aria-required="true">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="service_hours" placeholder="Service Hours" required value="{{ $official_value[1]->service_hours }}">
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
                                <button type="submit" class="btn yellow btn-block"><i class="fa fa-check"></i> Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
