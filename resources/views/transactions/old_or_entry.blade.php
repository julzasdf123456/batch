@extends('layouts.app')

<meta name="token" content="{{ csrf_token() }}">
<meta name="school" content="{{ env('APP_COMPANY_ABRV') }}">

@section('content')
<div id="app">
    <old-or-entry></old-or-entry>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Old Payment Entries</span>")
        })
    </script>
@endpush