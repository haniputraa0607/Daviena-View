@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/datemultiselect/jquery-ui.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/css/select2.min.css' }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/css/select2-bootstrap.min.css' }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/js/select2.min.js' }}" type="text/javascript"></script>
    <script>
        $('.selectpicker').selectpicker();
        $('#article_top').select2({
            placeholder: 'Select Articles',
            theme: 'bootstrap',
            width: '100%',
            allowClear: true,
        });
        $('#article').select2({
            placeholder: 'Select Articles',
            theme: 'bootstrap',
            width: '100%',
            allowClear: true,
        });
    </script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{ 'assets/global/plugins/select2/js/select2.min.js' }}" type="text/javascript"></script>
@endsection

@section('content')
    
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Landing Page Home</span>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <strong>Article Recommendation</strong>
            </li>
        </ul>
    </div><br>

    @include('layouts.notifications')

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue bold uppercase">Article Recommendation</span>
            </div>
        </div>

        <div class="portlet-body m-form__group row">
            <form class="form-horizontal" role="form" action="{{ route('landing_page.home.article_recommendation.update') }}"  method="post" enctype="multipart/form-data" id="myForm">
                <div class="col-md-12">
                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label>Article Top</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <select class="form-control" name="article_top" id="article_top">
                                            <option value="">Choose Article Top</option>
                                            @foreach($articles as $article)
                                                <option value="{{$article['id']}}" {{ $detail['article_top'] ?? ($detail['article_top'] == $article['id'] ) ? 'selected' : '' }}>{{$article['title']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label>Article Recommendation</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <select type="text" class="form-control" name="article[]" id="article" placeholder="Article" required multiple>
                                            <option value="">Choose Article</option>
                                            @foreach($articles as $article)
                                                @php
                                                $selected = '';
                                                foreach(json_decode($detail['article_recommendation']) as $key){
                                                    if($key == $article['id']){
                                                        $selected = 'selected';
                                                    }
                                                }
                                                @endphp
                                                <option value="{{$article['id']}}" {{ $selected }}>{{$article['title']}}</option>
                                            @endforeach
                                        </select>
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
