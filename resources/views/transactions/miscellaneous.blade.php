@extends('layouts.app')

<meta name="token" content="{{ csrf_token() }}">
<meta name="student-id" content="{{ $studentId }}">

@section('content')
<div id="app">
    <miscellaneous></miscellaneous>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Cashiering - </span> <strong>Miscellaneous Payments and Fees</strong>")
        })
    </script>
@endpush