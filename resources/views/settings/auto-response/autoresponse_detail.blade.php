@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-summernote/summernote.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/pages/scripts/components-select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
	<script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
	<script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-summernote/summernote.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
			$.fn.modal.Constructor.prototype.enforceFocus = function() {};
			$('.summernote').summernote({
				placeholder: 'No Data',
				tabsize: 2,
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
				height: 120
			});
        });
    </script>
    <script type="text/javascript">
        function visibleDiv(subject,value){
            if(subject == 'email'){
                if(value=='1'){
                    document.getElementById('div_email_subject').style.display = 'block';
                    document.getElementById('div_email_content').style.display = 'block';
                } else {
                    document.getElementById('div_email_subject').style.display = 'none';
                    document.getElementById('div_email_content').style.display = 'none';
                }
            }

            if(subject == 'push'){
                if(value=='1'){
                    document.getElementById('div_push_subject').style.display = 'block';
                    document.getElementById('div_push_content').style.display = 'block';
                    document.getElementById('div_push_image').style.display = 'block';
                    document.getElementById('div_push_clickto').style.display = 'block';
                    // document.getElementById('atd').style.display = 'block';
                    // document.getElementById('link').style.display = 'block';
                } else {
                    document.getElementById('div_push_subject').style.display = 'none';
                    document.getElementById('div_push_content').style.display = 'none';
                    document.getElementById('div_push_image').style.display = 'none';
                    document.getElementById('div_push_clickto').style.display = 'none';
                    // document.getElementById('atd').style.display = 'none';
                    // document.getElementById('link').style.display = 'none';
                }
            }

            if(subject == 'forward'){
                if(value=='1'){
                    document.getElementById('div_forward_address').style.display = 'block';
                    document.getElementById('div_forward_subject').style.display = 'block';
                    document.getElementById('div_forward_content').style.display = 'block';
                } else {
                    document.getElementById('div_forward_address').style.display = 'none';
                    document.getElementById('div_forward_subject').style.display = 'none';
                    document.getElementById('div_forward_content').style.display = 'none';
                }
            }
        }
    </script>
    <script type="text/javascript">
        function addEmailContent(param){
            var textvalue = $('#autocrm_email_content').val();

            var textvaluebaru = textvalue+" "+param;
            $('#autocrm_email_content').val(textvaluebaru);
            $('#autocrm_email_content').summernote('editor.saveRange');
            $('#autocrm_email_content').summernote('editor.restoreRange');
            $('#autocrm_email_content').summernote('editor.focus');
            $('#autocrm_email_content').summernote('editor.insertText', param);
        }

        function addEmailSubject(param){
            var textvalue = $('#autocrm_email_subject').val();
            var textvaluebaru = textvalue+" "+param;
            $('#autocrm_email_subject').val(textvaluebaru);
        }

        function addPushSubject(param){
            var textvalue = $('#autocrm_push_subject').val();
            var textvaluebaru = textvalue+" "+param;
            $('#autocrm_push_subject').val(textvaluebaru);
        }

        function addPushContent(param){
            var textvalue = $('#autocrm_push_content').val();
            var textvaluebaru = textvalue+" "+param;
            $('#autocrm_push_content').val(textvaluebaru);
        }

        function addForwardSubject(param){
            var textvalue = $('#autocrm_forward_email_subject').val();
            var textvaluebaru = textvalue+" "+param;
            $('#autocrm_forward_email_subject').val(textvaluebaru);
        }

        function addForwardContent(param){
            var textvalue = $('#autocrm_forward_email_content').val();

            var textvaluebaru = textvalue+" "+param;
            $('#autocrm_forward_email_content').val(textvaluebaru);
            $('#autocrm_forward_email_content').summernote('editor.saveRange');
            $('#autocrm_forward_email_content').summernote('editor.restoreRange');
            $('#autocrm_forward_email_content').summernote('editor.focus');
            $('#autocrm_forward_email_content').summernote('editor.insertText', param);
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
                <span>{{ $sub_title }}</span>
            </li>
            @endif
        </ul>
    </div><br>

    @include('layouts.notifications')

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-dark sbold uppercase font-blue">Auto Response {{ucfirst($detail['name'])}}</span>
            </div>
        </div>
        <div class="portlet-body form">
            <form class="form-horizontal" role="form" action="{{ url()->current() }}" method="post" enctype="multipart/form-data">
                <div class="form-body">
                {{-- Email --}}
                    <h4>Email</h4>
                    <div class="form-group">
                        <div class="input-icon right">
                            <label class="col-md-3 control-label">
                            Status
                            <span class="required" aria-required="true"> * </span>
                            <i class="fa fa-question-circle tooltips" data-original-title="Pilih enabled untuk mengedit template email auto response ketika {{strtolower(str_replace('-',' ',$detail['name']))}}" data-container="body"></i>
                            </label>
                        </div>
                        <div class="col-md-9">
                            <select name="email_toggle" id="autocrm_email_toogle" class="form-control select2" onChange="visibleDiv('email',this.value)">
                                <option value="0" @if(!$detail['email_toggle']) selected @endif>Disabled</option>
                                <option value="1" @if($detail['email_toggle']) selected @endif>Enabled</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="div_email_subject" @if(!$detail['email_toggle']) style="display:none;" @endif>
                        <div class="input-icon right">
                            <label class="col-md-3 control-label">
                            Subject
                            <span class="required" aria-required="true"> * </span>
                            <i class="fa fa-question-circle tooltips" data-original-title="Diisi dengan subjek email, tambahkan text replacer bila perlu" data-container="body"></i>
                            </label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" placeholder="Email Subject" class="form-control" name="email_subject" id="autocrm_email_subject" value="{{$detail['email_subject']}}">
                            <br>
                            You can use this variables to display user personalized information:
                            <br><br>
                            <div class="row">
                                @foreach($detail['custom_text_replace'] as $text)
                                    <div class="col-md-3" style="margin-bottom:5px;">
                                        <span class="btn dark btn-xs btn-block btn-outline var" data-toggle="tooltip" title="Text will be replace '{{ $text }}' with user's value" onClick="addEmailSubject('{{ $text }}');">{{ $text }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="div_email_content" @if(!$detail['email_toggle']) style="display:none;" @endif>
                        <div class="input-icon right">
                            <label class="col-md-3 control-label">
                            Content
                            <span class="required" aria-required="true"> * </span>
                            <i class="fa fa-question-circle tooltips" data-original-title="Diisi dengan konten email, tambahkan text replacer bila perlu" data-container="body"></i>
                            </label>
                        </div>
                        <div class="col-md-9">
                            <textarea name="email_content" id="autocrm_email_content" class="form-control summernote"><?php echo $detail['email_content'];?></textarea>
                            You can use this variables to display user personalized information:
                            <br><br>
                            <div class="row" >
                                @foreach($detail['custom_text_replace'] as $text)
                                    <div class="col-md-3" style="margin-bottom:5px;">
                                        <span class="btn dark btn-xs btn-block btn-outline var" data-toggle="tooltip" title="Text will be replace '{{ $text }}' with user's value" onClick="addEmailContent('{{ $text }}');">{{ $text }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <hr>
                {{-- Email --}}

                {{-- Push Notification --}}
                    <h4>Push Notification</h4>
                    <div class="form-group">
                        <div class="input-icon right">
                            <label class="col-md-3 control-label">
                            Status
                            <span class="required" aria-required="true"> * </span>
                            <i class="fa fa-question-circle tooltips" data-original-title="Pilih enabled untuk mengedit template push notification ketika {{strtolower(str_replace('-',' ',$detail['name']))}}" data-container="body"></i>
                            </label>
                        </div>
                        <div class="col-md-9">
                            <select name="push_toggle" id="autocrm_push_toogle" class="form-control select2" id="push_toogle" onChange="visibleDiv('push',this.value)">
                                <option value="0" @if(!$detail['push_toggle']) selected @endif>Disabled</option>
                                <option value="1" @if($detail['push_toggle']) selected @endif>Enabled</option>
                            </select>

                        </div>
                    </div>
                    <div class="form-group" id="div_push_subject" @if(!$detail['push_toggle']) style="display:none;" @endif>
                        <div class="input-icon right">
                            <label class="col-md-3 control-label">
                            Subject
                            <span class="required" aria-required="true"> * </span>
                            <i class="fa fa-question-circle tooltips" data-original-title="Subjek/ judul push notification, tambahkan text replacer bila perlu" data-container="body"></i>
                            </label>
                        </div>
                        <div class="col-md-9" >
                            <input type="text" placeholder="Push Notification Subject" class="form-control" name="push_subject" id="autocrm_push_subject" value="{{$detail['push_subject']}}">
                            <br>
                            You can use this variables to display user personalized information:
                            <br><br>
                            <div class="row">
                                @foreach($detail['custom_text_replace'] as $text)
                                    <div class="col-md-3" style="margin-bottom:5px;">
                                        <span class="btn dark btn-xs btn-block btn-outline var" data-toggle="tooltip" title="Text will be replace '{{ $text }}' with user's value" onClick="addPushSubject('{{ $text }}');">{{ $text }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="div_push_content" @if(!$detail['push_toggle']) style="display:none;" @endif>
                        <div class="input-icon right">
                            <label class="col-md-3 control-label">
                            Content
                            <span class="required" aria-required="true"> * </span>
                            <i class="fa fa-question-circle tooltips" data-original-title="Konten push notification, tambahkan text replacer bila perlu" data-container="body"></i>
                            </label>
                        </div>
                        <div class="col-md-9">
                            <textarea name="push_content" id="autocrm_push_content" class="form-control"><?php echo $detail['push_content'];?></textarea>
                            <br>
                            You can use this variables to display user personalized information:
                            <br><br>
                            <div class="row">
                                @foreach($detail['custom_text_replace'] as $text)
                                    <div class="col-md-3" style="margin-bottom:5px;">
                                        <span class="btn dark btn-xs btn-block btn-outline var" data-toggle="tooltip" title="Text will be replace '{{ $text }}' with user's value" onClick="addPushContent('{{ $text }}');">{{ $text }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="div_push_image" @if(!$detail['push_toggle']) style="display:none;" @endif>
                        <div class="input-icon right">
                            <label class="col-md-3 control-label">
                            Gambar
                            <i class="fa fa-question-circle tooltips" data-original-title="Sertakan gambar jika ada" data-container="body"></i>
                            </label>
                        </div>
                        <div class="col-md-9">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                    @if($detail['push_image'] == null)
                                    <img src="https://vignette.wikia.nocookie.net/simpsons/images/6/60/No_Image_Available.png/revision/latest?cb=20170219125728" id="autocrm_push_image" />
                                    @else
                                    <img src="{{env('STORAGE_URL_API')}}{{$detail['push_image']}}" id="autocrm_push_image" />
                                    @endif
                                </div>

                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                <div>
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new"> Select image </span>
                                        <span class="fileinput-exists"> Change </span>
                                        <input type="file"  accept="image/*" name="push_image"> </span>
                                    <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="div_push_clickto" @if(!$detail['push_toggle']) style="display:none;" @endif>
                        <div class="input-icon right">
                            <label class="col-md-3 control-label">
                            Click Action
                            <span class="required" aria-required="true"> * </span>
                            <i class="fa fa-question-circle tooltips" data-original-title="Action/ menu yang terbuka saat user membuka push notification" data-container="body"></i>
                            </label>
                        </div>
                        <div class="col-md-9">
                            {{-- <select name="autocrm_push_clickto" id="autocrm_push_clickto" class="form-control select2" onChange="fetchDetail(this.value)"> --}}
                            <select name="push_click_to" id="autocrm_push_clickto" class="form-control select2">
                                @foreach ($detail['push_click_to_option'] as $cto)
                                    <option value="{{ $cto }}" @if($detail['push_click_to'] == $cto) selected @endif>{{ $cto }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- <div class="form-group" id="atd" style="display:none;">
                        <div class="input-icon right">
                            <label class="col-md-3 control-label">
                            Action to Detail
                            <i class="fa fa-question-circle tooltips" data-original-title="Detail action/ menu yang akan terbuka saat user membuka push notification" data-container="body"></i>
                            </label>
                        </div>
                        <div class="col-md-9">
                            <select name="autocrm_push_id_reference" id="autocrm_push_id_reference" class="form-control select2">
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="link" style="display:none;">
                        <div class="input-icon right">
                            <label class="col-md-3 control-label">
                            Link
                            <i class="fa fa-question-circle tooltips" data-original-title="Jika action berupa link, masukkan alamat link nya disini" data-container="body"></i>
                            </label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" placeholder="https://" class="form-control" name="autocrm_push_link" value="{{$data['autocrm_push_link']}}">
                        </div>
                    </div> --}}
                    <hr>
                {{-- Push Notification --}}

                {{-- Forward --}}
                    <h4>Forward</h4>
                    <div class="form-group">
                        <div class="input-icon right">
                            <label class="col-md-3 control-label">
                            Status
                            <span class="required" aria-required="true"> * </span>
                            <i class="fa fa-question-circle tooltips" data-original-title="Pilih enabled untuk mengedit auto response forward ketika {{strtolower(str_replace('-',' ',$detail['name']))}}" data-container="body"></i>
                            </label>
                        </div>
                        <div class="col-md-9">
                            <select name="forward_toggle" id="autocrm_forward_toogle" class="form-control select2" class="form-control select2" onChange="visibleDiv('forward',this.value)">
                                <option value="0" @if(!$detail['forward_toggle']) selected @endif>Disabled</option>
                                <option value="1" @if($detail['forward_toggle']) selected @endif>Enabled</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="div_forward_address" @if(!$detail['forward_toggle']) style="display:none;" @endif>
                        <div class="input-icon right">
                            <label class="col-md-3 control-label">
                                Forward Address
                                <span class="required" aria-required="true"> * </span>
                                <i class="fa fa-question-circle tooltips" data-original-title="Masukkan alamat email tujuan forward untuk setiap auto response sistem. Pisahkan dengan koma." data-container="body"></i>
                            </label>
                        </div>
                        <div class="col-md-9">
                            <textarea name="forward_email" id="forward_email" class="form-control" placeholder="admin@gmail.com, admin_2@gmail.com"><?php echo $detail['forward_email']; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group" id="div_forward_subject" @if(!$detail['forward_toggle']) style="display:none;" @endif>
                        <div class="input-icon right">
                            <label class="col-md-3 control-label">
                                Subject
                                <span class="required" aria-required="true"> * </span>
                                <i class="fa fa-question-circle tooltips" data-original-title="Subjek pesan yang akan diforward, tambahkan text replacer bila perlu" data-container="body"></i>
                            </label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" placeholder="Forward Subject" class="form-control" name="forward_email_subject" id="autocrm_forward_email_subject" value="{{$detail['forward_email_subject']}}">
                            <br>
                            You can use this variables to display user personalized information:
                            <br><br>
                            <div class="row">
                                @foreach($detail['custom_text_replace'] as $text)
                                    <div class="col-md-3" style="margin-bottom:5px;">
                                        <span class="btn dark btn-xs btn-block btn-outline var" data-toggle="tooltip" title="Text will be replace '{{ $text }}' with user's value" onClick="addForwardSubject('{{ $text }}');">{{ $text }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="div_forward_content" @if(!$detail['forward_toggle']) style="display:none;" @endif>
                        <div class="input-icon right">
                            <label class="col-md-3 control-label">
                                Content
                                <span class="required" aria-required="true"> * </span>
                                <i class="fa fa-question-circle tooltips" data-original-title="Konten pesan, tambahkan text replacer bila perlu" data-container="body"></i>
                            </label>
                        </div>
                        <div class="col-md-9">
                            <textarea name="forward_email_content" id="autocrm_forward_email_content" class="form-control summernote"><?php echo $detail['forward_email_content']; ?></textarea>
                            You can use this variables to display user personalized information:
                            <br><br>
                            <div class="row">
                                @foreach($detail['custom_text_replace'] as $text)
                                    <div class="col-md-3" style="margin-bottom:5px;">
                                        <span class="btn dark btn-xs btn-block btn-outline var" data-toggle="tooltip" title="Text will be replace '{{ $text }}' with user's value" onClick="addForwardContent('{{ $text }}');">{{ $text }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                {{-- Forward --}}
                    @if (in_array(41, session('granted_features')) || session('user_role') == 'super_admin')
                    <div class="form-actions">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-offset-5 col-md-5">
                            <input type="hidden" name="code" value="{{$detail['code']}}">
                                <button type="submit" class="btn yellow"><i class="fa fa-save"></i> Update</button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
