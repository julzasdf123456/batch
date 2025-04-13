<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="student-subjects-table">
            <thead>
            <tr>
                <th>Studentid</th>
                <th>Subjectid</th>
                <th>Classid</th>
                <th>Teacherid</th>
                <th>Firstgradinggrade</th>
                <th>Secondgradinggrade</th>
                <th>Thirdgradinggrade</th>
                <th>Fourthgradinggrade</th>
                <th>Averagegrade</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($studentSubjects as $studentSubjects)
                <tr>
                    <td>{{ $studentSubjects->StudentId }}</td>
                    <td>{{ $studentSubjects->SubjectId }}</td>
                    <td>{{ $studentSubjects->ClassId }}</td>
                    <td>{{ $studentSubjects->TeacherId }}</td>
                    <td>{{ $studentSubjects->FirstGradingGrade }}</td>
                    <td>{{ $studentSubjects->SecondGradingGrade }}</td>
                    <td>{{ $studentSubjects->ThirdGradingGrade }}</td>
                    <td>{{ $studentSubjects->FourthGradingGrade }}</td>
                    <td>{{ $studentSubjects->AverageGrade }}</td>
                    <td>{{ $studentSubjects->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['studentSubjects.destroy', $studentSubjects->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('studentSubjects.show', [$studentSubjects->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('studentSubjects.edit', [$studentSubjects->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $studentSubjects])
        </div>
    </div>
</div>
