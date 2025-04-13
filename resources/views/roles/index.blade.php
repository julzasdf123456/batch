@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Roles</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('roles.create') }}">
                        Add New Role
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title">Roles Available in the System</span>
            </div>
            @include('roles.table')
        </div>
    </div>

@endsection
