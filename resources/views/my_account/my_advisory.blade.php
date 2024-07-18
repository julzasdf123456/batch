@extends('layouts.app')

<meta name="teacher-id" content="{{ Auth::user()->TeacherId }}">

@section('content')
<div id="app">
    <my-advisory></my-advisory>
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>My Advisory</span>")
        })
    </script>
@endpush