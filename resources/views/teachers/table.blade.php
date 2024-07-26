<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover" id="teachers-table">
            <thead>
            <tr>
                <th>Fullname</th>
                <th>Designation</th>
                <th>Subjectexpertise</th>
                <th>Department</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->FullName }}</td>
                    <td>{{ $teacher->Designation }}</td>
                    <td>{{ $teacher->SubjectExpertise }}</td>
                    <td>{{ $teacher->Department }}</td>
                    <td>{{ $teacher->Status }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['teachers.destroy', $teacher->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            @if (Auth::user()->hasAnyPermission(['god permission', 'view teachers']))
                                <a href="{{ route('teachers.show', [$teacher->id]) }}"
                                    class='btn btn-default btn-xs'>
                                    <i class="far fa-eye"></i>
                                </a>
                            @endif
                            
                            @if (Auth::user()->hasAnyPermission(['god permission', 'edit teachers']))
                                <a href="{{ route('teachers.edit', [$teacher->id]) }}"
                                    class='btn btn-default btn-xs'>
                                        <i class="far fa-edit"></i>
                                    </a>
                            @endif
                            
                            @if (Auth::user()->hasAnyPermission(['god permission', 'delete teachers']))
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
            @include('adminlte-templates::common.paginate', ['records' => $teachers])
        </div>
    </div>
</div>
