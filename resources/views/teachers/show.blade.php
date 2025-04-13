@extends('layouts.app')

<meta name="teacher-id" content="{{ $id }}">
<meta name="token" content="{{ csrf_token() }}">

@section('content')
<div id="app">
    <view-teacher></view-teacher>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'></span> <strong>Teacher/Instructor View</strong>")
        })
    </script>
@endpush