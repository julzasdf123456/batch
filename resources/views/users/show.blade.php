@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <span class="text-muted">User:</span>
                    <h1>
                        {{ $users->name }}
                        <a href="{{ route('users.edit', [$users->id]) }}" style="font-size: .6em;" title="Edit Details" class="text-muted"><i class="fas fa-pen ico-tab-left"></i></a>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('users.index') }}">
                                                    Back
                                            </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="row">
            {{-- users info --}}
            <div class="col-lg-6">
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title">User Info</span>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-sm">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Username</td>
                                    <td>{{ $users->username }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email</td>
                                    <td>{{ $users->email }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Color Profile</td>
                                    <td>{{ $users->ColorProfile }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Password Hash</td>
                                    <td>{{ $users->password }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Signed In On</td>
                                    <td>{{ date('M d, Y h:i A', strtotime($users->created_at)) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Teacher/Faculty ID</td>
                                    <td>{{ $users->TeacherId }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- users permissions --}}
            <div class="col-lg-6">
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title">Roles and Permissions Set to {{ $users->name }}</span>

                        <div class="card-tools">
                            <a href="{{ route('users.add-roles', $users->id) }}" class="btn btn-tool btn-sm" title="Configure Role and Permissions">
                                <i class="fas fa-key"></i>
                            </a>

                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <p class="text-muted no-pads">Roles</p>
                        <table class="table table-sm table-hover">
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td><i class="fas fa-shield-alt text-primary ico-tab"></i>{{ $role->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <p class="text-muted no-pads">Permissions Allowed</p>
                        <table class="table table-sm table-hover">
                            <tbody>
                                @foreach ($permissions as $item)
                                    <tr>
                                        <td><i class="fas fa-check-circle text-success ico-tab"></i>{{ $item->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection
