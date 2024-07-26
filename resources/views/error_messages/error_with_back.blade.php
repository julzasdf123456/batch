<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

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
        <h1 class="text-muted">{{ $errorCode }}</h1>
        <p>{{ $message }}</p>
        <a href="javascript:history.back()" class="btn btn-default">
                <i class="fas fa-arrow-left ico-tab"></i>Go Back
        </a>
    </div>
</body>
</html>


