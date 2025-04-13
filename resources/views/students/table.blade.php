<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="students-table">
            <thead>
            <tr>
                <th>Firstname</th>
                <th>Middlename</th>
                <th>Lastname</th>
                <th>Suffix</th>
                <th>Birthdate</th>
                <th>Gender</th>
                <th>Sitio</th>
                <th>Barangay</th>
                <th>Town</th>
                <th>Contactnumber</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->FirstName }}</td>
                    <td>{{ $student->MiddleName }}</td>
                    <td>{{ $student->LastName }}</td>
                    <td>{{ $student->Suffix }}</td>
                    <td>{{ $student->Birthdate }}</td>
                    <td>{{ $student->Gender }}</td>
                    <td>{{ $student->Sitio }}</td>
                    <td>{{ $student->Barangay }}</td>
                    <td>{{ $student->Town }}</td>
                    <td>{{ $student->ContactNumber }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['students.destroy', $student->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('students.show', [$student->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('students.edit', [$student->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $students])
        </div>
    </div>
</div>
