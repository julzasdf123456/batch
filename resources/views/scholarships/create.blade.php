@php
    use App\Models\IDGenerator;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                    Create New Scholarship Grant
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        <div class="row">
            <div class="col-lg-8 offset-md-2">
                @include('adminlte-templates::common.errors')

                <div class="card shadow-none">

                    {!! Form::open(['route' => 'scholarships.store']) !!}

                    <div class="card-body">

                        <div class="row">
                            <input type="hidden" name="id" value="{{ IDGenerator::generateIDandRandString() }}">
                            @include('scholarships.fields')
                        </div>

                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('scholarships.index') }}" class="btn btn-default"> Cancel </a>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
        
    </div>
@endsection
