@php
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>
                    Create New Subject
                    </h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card shadow-none">

            {!! Form::open(['route' => 'subjects.store']) !!}

            <input type="hidden" name="id" value="{{ IDGenerator::generateIDandRandString() }}">

            <div class="card-body">

                <div class="row">
                    @include('subjects.fields')
                </div>

            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('subjects.index') }}" class="btn btn-default"> Cancel </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
