@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div id="app">
            <dashboard-index></dashboard-index>
        </div>
        @vite('resources/js/app.js')
    </div>
</div>
@endsection
