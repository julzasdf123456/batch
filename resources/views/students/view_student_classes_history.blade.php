@extends('layouts.app')

<meta name="student-id" content="{{ $id }}">
@section('content')
    <div id="app">
        <view-student-classes-history></view-student-classes-history>
    </div>
    @vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function () {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'></span> <strong>Student Classes History View</strong>")
        })
    </script>
@endpush