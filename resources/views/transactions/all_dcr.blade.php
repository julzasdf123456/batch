@extends('layouts.app')

<meta name="token" content="{{ csrf_token() }}">

@section('content')
<div id="app">
    <all-dcr></all-dcr>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>View All </span> <strong>Daily Collection Report</strong>")
        })
    </script>
@endpush