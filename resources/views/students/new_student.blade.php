@extends('layouts.app')

<meta name="token" content="{{ csrf_token() }}">

@section('content')
<div id="app">
    <new-student></new-student>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Enrollment Step 1. </span> <strong>Create Student Profile</strong>")
        })
    </script>
@endpush