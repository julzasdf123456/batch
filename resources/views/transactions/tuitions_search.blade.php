@extends('layouts.app')

<meta name="token" content="{{ csrf_token() }}">

@section('content')
<div id="app">
    <tuitions-search></tuitions-search>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Cashiering - </span> <strong>Tuition Fees</strong>")
        })
    </script>
@endpush