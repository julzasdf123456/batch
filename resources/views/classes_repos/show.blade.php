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
                    <a class="btn btn-default float-right"
                       href="{{ route('classesRepos.index') }}">
                                                    Back
                                            </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title text-muted">Current Classes and Sections</span>
            </div>
            <div class="card-body">
                <div class="row">
                    @include('classes_repos.show_fields')
                </div>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
@endsection
