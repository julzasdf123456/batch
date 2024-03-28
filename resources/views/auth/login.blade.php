<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} | Login</title>

    <!-- Tell the browser to be responsive to screen width -->
    <link rel="stylesheet" href="{{ URL::asset('css/source_sans_pro.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/all.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap4toggle.css'); }} ">
          
    <link rel="stylesheet" href="{{ URL::asset('css/adminlte.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/bootstrapdatetimepicker.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/jqueryui.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/select2.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/select2.bootstrap4.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/fullcalendar.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/sweetalert2.min.css'); }}">

    <link rel="stylesheet" href="{{ URL::asset('css/daterangepicker.min.css'); }}">
    
    <link rel="stylesheet" href="{{ URL::asset('css/style.css'); }}">

    <link rel="stylesheet" href="{{ URL::asset('css/bstreeview.css'); }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/home') }}" style="width: 100%; text-align: center; font-size: 1.4em;"><strong>{{ config('app.name') }}</strong></a>
        </div>
    
        <!-- /.login-logo -->
    
        <!-- /.login-box-body -->
        <div class="card shadow-none shadow-soft">
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>
    
                <form method="post" action="{{ url('/login') }}">
                    @csrf
    
                    <div class="input-group mb-3">
                        <input type="text"
                               name="username"
                               value="{{ old('username') }}"
                               placeholder="Username"
                               class="form-control @error('username') is-invalid @enderror" autofocus>
                        @error('username')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
    
                    <div class="input-group mb-3">
                        <input type="password"
                               name="password"
                               placeholder="Password"
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
    
                    </div>
    
                    <div class="row">
                        {{-- <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">Remember Me</label>
                            </div>
                        </div> --}}
    
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fas fa-unlock"></i> Sign In</button>
                        </div>
    
                    </div>
                </form>
                <p class="text-center text-muted" style="margin-top: 12px;">or</p>
                <p class="text-center">
                    <a href="{{ route('register') }}">Create a New Account</a>
                </p>
                <p class="mb-1 text-center" style="margin-top: 18px;">
                    <a class="text-muted" href="{{ route('password.request') }}" style="font-size: .85em;">I forgot my password</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    
    </div>
    
    
    <p class="text-center text-muted" style="font-size: .9em; position: fixed; bottom: 10px;">{{ env('APP_FULLNAME') }} <br> All Rights Reserved @ {{ date('Y') }} </p>
<!-- /.login-box -->

<script src="{{ URL::asset('js/jquery.min.css'); }}"></script>

{{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}
<script src="{{ URL::asset('js/jqueryui.css'); }}"></script>

{{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" 
        crossorigin="anonymous"></script> --}}
<script src="{{ URL::asset('js/popper.min.js'); }}"></script>

{{-- <script src="https://adminlte.io/themes/v3/plugins/bootstrap/js/bootstrap.bundle.min.js"></script> --}}
<script src="{{ URL::asset('js/bootstrap.bundle.min.js'); }}"></script>
        
{{-- <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script> --}}
<script src="{{ URL::asset('js/bscustomfileinput.min.js'); }}"></script>


<!-- AdminLTE App -->
{{-- <script src="https://adminlte.io/themes/v3/dist/js/adminlte.min.js"></script> --}}
<script src="{{ URL::asset('js/adminlte.min.js'); }}"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"
        integrity="sha512-rmZcZsyhe0/MAjquhTgiUcb4d9knaFc7b5xAfju483gbEXTkeJRUMIPk6s3ySZMYUHEcjKbjLjyddGWMrNEvZg=="
        crossorigin="anonymous"></script> --}}
<script src="{{ URL::asset('js/moment.js'); }}"></script>
<script src="{{ URL::asset('js/moment-timezone.js'); }}"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"
        integrity="sha512-GDey37RZAxFkpFeJorEUwNoIbkTwsyC736KNSYucu1WJWFK9qTdzYub8ATxktr6Dwke7nbFaioypzbDOQykoRg=="
        crossorigin="anonymous"></script> --}}
<script src="{{ URL::asset('js/datetimepicker.min.js'); }}"></script>

{{-- <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script> --}}
<script src="{{ URL::asset('js/bootstrap4toggle.min.js'); }}"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js" integrity="sha512-J+763o/bd3r9iW+gFEqTaeyi+uAphmzkE/zU8FxY6iAvD3nQKXa+ZAWkBI9QS9QkYEKddQoiy0I5GDxKf/ORBA==" crossorigin="anonymous"></script> --}}
<script src="{{ URL::asset('js/bootstrapswitch.min.js'); }}"></script>

{{-- <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script> --}}
<script src="{{ URL::asset('js/lordicon.js'); }}"></script>

{{-- <script src="https://adminlte.io/themes/v3/plugins/chart.js/Chart.min.js"></script> --}}
<script src="{{ URL::asset('js/chart.min.js'); }}"></script>

{{-- <script src="https://www.jqueryscript.net/demo/jQuery-Plugin-To-Connect-HTML-Elements-with-Lines-HTML-SVG-Connect/jquery.html-svg-connect.js"></script> --}}
<script src="{{ URL::asset('js/svgconnect.js'); }}"></script>

{{-- <script src="https://adminlte.io/themes/v3/plugins/select2/js/select2.full.min.js"></script> --}}
<script src="{{ URL::asset('js/select2min.js'); }}"></script>

{{-- CALENDAR --}}
{{-- <script src="https://adminlte.io/themes/v3/plugins/jquery-ui/jquery-ui.min.js"></script> --}}
{{-- <script src="https://adminlte.io/themes/v3/plugins/fullcalendar/main.js"></script> --}}
<script src="{{ URL::asset('js/jqueryuicalendar.min.js'); }}"></script>
<script src="{{ URL::asset('js/calendarfulljs.min.js'); }}"></script>

{{-- SWEETALERT2 --}}
<script src="{{ URL::asset('js/sweetalert2.all.min.js'); }}"></script>

{{-- INPUT MASK --}}
<script src="{{ URL::asset('js/inputmask.min.js'); }}"></script>
<script src="{{ URL::asset('js/daterangepicker.min.js'); }}"></script>
<script src="{{ URL::asset('js/bstreeview.js'); }}"></script>

</body>
</html>
