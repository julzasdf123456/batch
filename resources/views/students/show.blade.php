@extends('layouts.app')

<meta name="student-id" content="{{ $id }}">
@section('content')
<div id="app">
    <view-student></view-student>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'></span> <strong>Student View</strong>")
        })
    </script>
@endpush
