@extends('layouts.app')

<meta name="student-id" content="{{ $studentId }}">

@section('content')
<div id="app">
    <transfer></transfer>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Student Section/Strand Transfer Wizzard</span>")
        })
    </script>
@endpush