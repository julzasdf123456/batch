@extends('layouts.app')

<meta name="class-id" content="{{ $classId }}">
<meta name="teacher-id" content="{{ $teacherId }}">
<meta name="school-year-id" content="{{ $syId }}">
<meta name="subject-id" content="{{ $subjectId }}">
<meta name="olv-input" content="{{ env('OLV_EDIT_MODE') }}">
<meta name="source" content="{{ $source }}">

@section('content')
    <div id="app">
        <view-class></view-class>
    </div>
    @vite('resources/js/app.js')
@endsection
@push('page_scripts')
    <script>
        $(document).ready(function () {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Class View</span>")
        })
    </script>
@endpush