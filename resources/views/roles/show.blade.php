@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <span class="text-muted">Role</span>
                    <h1>
                        {{ $roles->name }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('roles.index') }}">
                                                    Back
                                            </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title">Permissions and Access Allowed in this Role</span>

                <div class="card-tools">
                    <a href="{{ route('roles.add-permissions', [$roles->id]) }}" title="Edit Permissions for this role"><i class="fas fa-key"></i></a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table">
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                    </tr>
                    @php
                        $permissions = $roles->permissions;
                    @endphp
                    @foreach ($permissions as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
