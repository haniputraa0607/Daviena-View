<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>{{env('TITLE', 'CRM System')}} | User Login</title>

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="{{env('TITLE', 'CMS System')}} Admin Portal" name="description" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

		<meta property="og:description" content="Casion" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="{{env('TITLE', 'Casion')}}" />
		<meta property="og:image" content="{{env('STORAGE_URL_VIEW') }}{{ ('images/logo.svg')}}" />
		<meta property="og:image:width" content="200" />
		<meta property="og:image:height" content="200" />
		<link href="{{env('STORAGE_URL_VIEW') }}{{ ('images/logo.svg')}}" rel="image_src" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<link rel="shortcut icon" sizes="200x200" href="{{env('STORAGE_URL_VIEW') }}{{ ('images/logo.svg')}}">

        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/pages/css/login.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
		<link rel="shortcut icon" href="{{env('STORAGE_URL_VIEW') }}{{ ('images/logo.svg')}}" />

        <style type="text/css">
            .captcha_div > div{
                margin-left: auto;
                margin-right: auto;
            }
        </style>

	</head>
    <!-- END HEAD -->

    <body class="login" @if(env('BACKGROUND_LOGIN')) style="background-color:{{env('BACKGROUND_LOGIN')}} !important" @endif>
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="index.html">
                <img src="{{env('STORAGE_URL_VIEW') }}{{ ('images/logo.svg')}}" alt="" style="height:100px; margin: 20px" /> </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <form class="login-form" action="{{url('login')}}" method="post">
			{{csrf_field()}}
                <h3 class="form-title">Sign In</h3>
				@include('layouts.notifications')
                <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span> Enter valid email and password. </span>
                </div>
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">Phone</label>
                    <input class="form-control form-control-solid placeholder-no-fix" type="phone" autocomplete="off" placeholder="Phone" name="username" value="{{ old('username') }}" /> </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                    <input class="form-control form-control-solid placeholder-no-fix" maxlength="6" type="password" autocomplete="off" placeholder="Password" name="password" required />
				</div>
				<div class="form-group" style="margin-bottom: 0">
                    {!!  GoogleReCaptchaV3::renderField('captcha_div','login', 'captcha_div') !!}
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn uppercase btn-block" style="background: {{env('BUTTON_LOGIN')}}; color: #fff">Login</button>
                </div>
            </form>
            <!-- END LOGIN FORM -->

        </div>
        <div class="copyright" style="color: #fff"> Copyright © <?php echo date("Y"); ?> Technopartner Indonesia</div>
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
        <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/pages/scripts/login.min.js')}}" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
        {!!  GoogleReCaptchaV3::init() !!}

        <script>
            $(document).ready(function()
            {
                $('#clickmewow').click(function()
                {
                    $('#radio1003').attr('checked', 'checked');
                });
            })
        </script>
    </body>

</html>
