@extends('layouts.app')

<meta name="token" content="{{ csrf_token() }}">

@section('content')
<div id="app">
    <existing-student-enroll></existing-student-enroll>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Enrollment Step 1. </span> <strong>Select Existing Student</strong>")
        })
    </script>
@endpush