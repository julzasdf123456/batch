@extends('layouts.app')

@section('content')
<div id="app">
    <notifier></notifier>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // $('body').addClass('sidebar-collapse')
            $('#page-title').html("<strong>Send SMS</strong><span class='text-muted'> to all Contacts</span>")
        })
    </script>
@endpush
