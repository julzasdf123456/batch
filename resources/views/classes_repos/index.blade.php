@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>
                        Classes and Sections
                    </h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('classesRepos.create') }}">
                        Add New
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
                <span class="card-title text-muted">Current Classes and Sections</span>
            </div>
            @include('classes_repos.table')
            <div class="card-footer"></div>
        </div>
    </div>

@endsection
