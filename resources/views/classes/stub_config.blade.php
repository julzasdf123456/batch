@extends('layouts.app')

<meta name="class-id" content="{{ $classId }}">
<meta name="viewed-in" content="admin">
<meta name="school" content="{{ env('APP_COMPANY_ABRV') }}">

@section('content')
<div id="app">
    <stub-config></stub-config>
</div>
@vite('resources/js/app.js')
@endsection
@push('page_scripts')
    <script>
        $(document).ready(function() {
            // $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Grade Stub Print Configuration</span>")
        })
    </script>
@endpush