@extends('layouts.app')

<meta name="token" content="{{ csrf_token() }}">
<meta name="studentId" content="{{ $studentId }}">

@section('content')
<div id="app">
    <merge-to></merge-to>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Merge Students Wizzard</span>")
        })
    </script>
@endpush