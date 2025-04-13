@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 offset-lg-3 col-md-12">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <span class="text-muted">Add Permissions to</span>
                            <h1>{{ $role->name }}</h1>
                        </div>
                    </div> 
                </div>
            </section>

            <div class="card shadow-none">
                <form method="POST" class="form-horizontal" action="{{ route('roles.create-role-permissions') }}">
                    <div class="card-header">
                        <span class="card-title">Select Permissions</span>
                    </div>
                    <div class="card-body">                
                        @csrf
                        @foreach ($permissions as $item)
                            <div class="form-check">
                                <input type="checkbox" name="item[]" id="{{ $item->id }}" class="form-check-input" value="{{ $item->name }}" @if($role->permissions) @if(in_array($item->name, $role->permissions->pluck('name')->toArray())) checked @endif @endif>
                                {{ Form::label($item->id, $item->name, ['class' => 'form-check-label']) }}
                            </div>
                        @endforeach
                        
                        <input type="hidden" name="roleId" value="{{ $role->id }}">
            
                        
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-check-circle ico-tab-mini"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
@endsection