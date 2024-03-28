<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="student-classes-table">
            <thead>
            <tr>
                <th>Classid</th>
                <th>Studentid</th>
                <th>Status</th>
                <th>Enrollmentornumber</th>
                <th>Enrollmentordate</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($studentClasses as $studentClass)
                <tr>
                    <td>{{ $studentClass->ClassId }}</td>
                    <td>{{ $studentClass->StudentId }}</td>
                    <td>{{ $studentClass->Status }}</td>
                    <td>{{ $studentClass->EnrollmentORNumber }}</td>
                    <td>{{ $studentClass->EnrollmentORDate }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['studentClasses.destroy', $studentClass->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('studentClasses.show', [$studentClass->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('studentClasses.edit', [$studentClass->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
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
            @include('adminlte-templates::common.paginate', ['records' => $studentClasses])
        </div>
    </div>
</div>
