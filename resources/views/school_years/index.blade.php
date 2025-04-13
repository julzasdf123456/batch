@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>School Years</h4>
                </div>
                {{-- <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('schoolYears.create') }}">
                        Add New
                    </a>
                </div> --}}
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card shadow-none">
            @include('school_years.table')
        </div>
    </div>

@endsection
