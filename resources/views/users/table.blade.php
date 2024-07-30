<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover" id="users-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Colorprofile</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->ColorProfile }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('users.show', [$user->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', [$user->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            <a title="Configure Roles and Permissions" href="{{ route('users.add-roles', [$user->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="fas fa-key"></i>
                            </a>
                            <button onclick="updatePasswordAdmin(event, `{{ $user->id }}`)" class='btn btn-default btn-xs'  title="Reset Password">
                                <i class="fas fa-unlock-alt"></i>
                            </button>
                            @if (Auth::id() == '1')
                                {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            @endif
                            
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $users])
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        function updatePasswordAdmin(event, userid) {
            event.preventDefault()
            Swal.fire({
                title: 'Reset User Password',
                html:
                    `<p>Validate new password below.</p>
                    <input id="password" class="form-control" type="password" placeholder="Enter new password...">
                    <input id="password-confirm" class="form-control mt-2" type="password" placeholder="Confirm password...">`,
                focusConfirm: false,
                confirmButtonText: 'Update Password',
                preConfirm: () => {
                    const pword = document.getElementById('password').value;
                    const pwordConfirm = document.getElementById('password-confirm').value;

                    if (!pword || !pwordConfirm) {
                        Swal.showValidationMessage('Both passwords are required.');
                        return false;
                    }

                    if (pword !== pwordConfirm) {
                        Swal.showValidationMessage('Passwords do not match.');
                        return false;
                    }

                    return fetch('{{ route("users.update-password-admin") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            password: pword,
                            password_confirmation: pwordConfirm,
                            user_id : userid,
                        })
                    })
                    .then(response => response.json().then(data => ({
                        status: response.status,
                        body: data
                    })))
                    .then(({ status, body }) => {
                        if (status !== 200) {
                            console.log('Server response:', body);
                            Swal.showValidationMessage(`Request failed: ${body.message || 'Unknown error'}`);
                            return false;
                        }
                        return body
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                        console.log(error.message)
                    })
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Success', 'Password updated successfully', 'success');
                }
            })
        }
    </script>
@endpush
