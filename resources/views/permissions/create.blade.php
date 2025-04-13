@extends('layouts.app')

@section('content')

    <div class="content px-3">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-12">
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <h1>Create New Permission Gate</h1>
                            </div>
                        </div>
                    </div>
                </section>

                @include('adminlte-templates::common.errors')

                <div class="card shadow-none">

                    {!! Form::open(['route' => 'permissions.store']) !!}

                    <div class="card-body">

                        <input type="hidden" name="guard_name" value="web">
                        <div class="row">
                            <!-- Name Field -->
                            <div class="form-group col-lg-12">
                                {!! Form::label('name', 'Permission Name:') !!}
                                {!! Form::text('name', null, ['class' => 'form-control', 'required', 'maxlength' => 255, 'maxlength' => 255, 'autofocus' => true]) !!}
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('permissions.index') }}" class="btn btn-default"> Cancel </a>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
