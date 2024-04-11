@extends('layouts.app')

@section('content')
<div id="app">
    <search-students></search-students>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'></span> <strong>All Students</strong>")
        })
    </script>
@endpush