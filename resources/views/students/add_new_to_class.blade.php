@extends('layouts.app')

<meta name="token" content="{{ csrf_token() }}">
<meta name="studentId" content="{{ $studentId }}">

@section('content')
<div id="app">
    <add-new-to-class></add-new-to-class>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Add a Student Manually</span> <strong> (Add to Class)</strong>")
        })
    </script>
@endpush