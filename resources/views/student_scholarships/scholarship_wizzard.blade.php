@extends('layouts.app')

<meta name="student-id" content="{{ $id }}">
<meta name="from" content="{{ $from }}">
<meta name="scholarship-options" content="{{ env('SCHOLARSHIP_DEDUCTION') }}">
@section('content')
<div id="app">
    <scholarship-wizzard></scholarship-wizzard>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<strong>Scholarship Wizzard</strong><span class='text-muted'> - Add Scholarship Grant</span>")
        })
    </script>
@endpush
