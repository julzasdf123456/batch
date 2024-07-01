@extends('layouts.app')

@section('content')
<div id="app">
    <miscellaneous-search></miscellaneous-search>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Cashiering - </span> <strong>Miscellaneous Payments and Fees</strong>")
        })
    </script>
@endpush