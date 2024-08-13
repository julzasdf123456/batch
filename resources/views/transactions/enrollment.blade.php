@extends('layouts.app')

<meta name="token" content="{{ csrf_token() }}">

@section('content')
<div id="app">
    @if (env("TUITION_PROPAGATION_PRESET") === 'STATIC_ENROLLMENT_FEE')
        <enrollment-transactions></enrollment-transactions>
    @else
        <enrollment-flexible></enrollment-flexible>
    @endif
    
</div>
@vite('resources/js/app.js')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<span class='text-muted'>Cashiering - </span> <strong>Enrollment Fees</strong>")
        })
    </script>
@endpush