<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Not Allowed</title>

    <link rel="stylesheet" href="{{ URL::asset('css/source_sans_pro.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/all.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap4toggle.css'); }} ">
          
    <link rel="stylesheet" href="{{ URL::asset('css/adminlte.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/bootstrapdatetimepicker.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/sweetalert2.min.css'); }}">
    
    <link rel="stylesheet" href="{{ URL::asset('css/style.css'); }}">
</head>
<body style="width: 100%;">
    <div class="error-messages">
        <h1 class="text-muted">403</h1>
        <p>You are not allowed to access the system yet. Kindly contact your system administrator for access assistance.</p>
        <a href="#" class="btn btn-default"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt ico-tab"></i>Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</body>
</html>


