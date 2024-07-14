@extends('layouts.app')

<meta name="teacher-id" content="{{ Auth::user()->TeacherId }}">

@section('content')
<div id="app">
    <my-classes></my-classes>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>My Classes</span>")
        })
    </script>
@endpush
