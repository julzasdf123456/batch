@extends('layouts.app')

@section('content')

    <div class="content px-3">

        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-12">
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <h1>
                                Create Roles
                                </h1>
                            </div>
                        </div>
                    </div>
                </section>
                
                @include('adminlte-templates::common.errors')

                <div class="card shadow-none">

                    {!! Form::open(['route' => 'roles.store']) !!}

                    <div class="card-body">
                        <input type="hidden" name="guard_name" value="web">
                        <div class="row">
                            @include('roles.fields')
                        </div>

                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('roles.index') }}" class="btn btn-default"> Cancel </a>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
        
    </div>
@endsection
