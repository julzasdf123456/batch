@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>
                        Edit This Teacher's Info
                    </h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')
        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-12">
                <div class="card shadow-none">

                    {!! Form::model($teachers, ['route' => ['teachers.update', $teachers->id], 'method' => 'patch']) !!}
        
                    <div class="card-body">
                        <div class="row">
                            @include('teachers.fields')
                        </div>
                    </div>
        
                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('teachers.index') }}" class="btn btn-default"> Cancel </a>
                    </div>
        
                    {!! Form::close() !!}
        
                </div>
            </div>
        </div>
        
    </div>
@endsection
