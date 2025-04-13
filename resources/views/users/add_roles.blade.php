@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Set Roles for {{ $users->name }}</h5>
                </div>
            </div>
        </div>
    </section>
    
    <div>
        <form class="form" method="POST" action="{{ url('users/create-user-roles') }}">

            <div class="row">

                <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4">
                    <div class="card shadow-none">
                        <div class="card-header">
                            <span class="card-title">Customize Roles</span>
                        </div>

                        <div class="card-body">
                            @csrf
                            @foreach($roles as $role)  
                                <div class="form-check">  
                                    <input type="checkbox" class="form-check-input" id="{{ $role->id }}" value="{{ $role->name }}" name="roles[]"  @if($users->roles) @if(in_array($role->name, $users->roles->pluck('name')->toArray())) checked @endif @endif>
                                    <label for="{{ $role->id }}" class="form-check-label">{{ $role->name }}</label>
                                </div>    
                            @endforeach
                            <!-- ADD USER ID -->
                            <input type="hidden" name="userId" value="{{ $users->id }}">     
                        </div>

                        <div class="card-footer">
                            <!-- SUBMIT -->
                            <button type="submit" class="btn btn-primary" style="margin-bottom: 15px;"><i class="fas fa-check-circle"></i> Save and Apply</button> 
                        </div>
                    </div>
                </div>
                
                          
            
            </div>
        </form>
    </div>
@endsection

@push('page_scripts')
    <script>
        function checkAll(roleId) {
            var isChecked = $('#roles-' + roleId).prop('checked')

            $('input[type="checkbox"][role-id="' + roleId + '"]').each(function(){
                $(this).prop('checked', isChecked);
            })
        }
    </script>
@endpush