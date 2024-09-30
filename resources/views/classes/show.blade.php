@extends('layouts.app')

<meta name="teacher-id" content="{{ $adviser }}">
<meta name="school-year-id" content="{{ $schoolYearId }}">
<meta name="class-id" content="{{ $classId }}">
<meta name="am-in-threshold" content="{{ env('STUDENT_IN_AM_THRESHOLD') }}">
<meta name="pm-out-threshold" content="{{ env('STUDENT_OUT_PM_THRESHOLD') }}">
<meta name="viewed-in" content="admin">
<meta name="school" content="{{ env('APP_COMPANY_ABRV') }}">

@section('content')
<div id="app">
    <view-advisory></view-advisory>
</div>
@vite('resources/js/app.js')
@endsection
@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Advisory View</span>")
        })
    </script>
@endpush