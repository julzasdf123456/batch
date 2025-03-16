@extends('layouts.app')

<meta name="school" content="{{ env('APP_COMPANY_ABRV') }}">

@section('content')
<div id="app">
    <ledger-management></ledger-management>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Cashiering - </span> <strong>Student Ledger Management</strong>")
        })
    </script>
@endpush