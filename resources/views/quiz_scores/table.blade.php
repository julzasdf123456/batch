<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="quiz-scores-table">
            <thead>
            <tr>
                <th>Studentid</th>
                <th>Subjectid</th>
                <th>Classid</th>
                <th>Teacherid</th>
                <th>Gradingperiod</th>
                <th>Userid</th>
                <th>Studentscore</th>
                <th>Totalscore</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($quizScores as $quizScores)
                <tr>
                    <td>{{ $quizScores->StudentId }}</td>
                    <td>{{ $quizScores->SubjectId }}</td>
                    <td>{{ $quizScores->ClassId }}</td>
                    <td>{{ $quizScores->TeacherId }}</td>
                    <td>{{ $quizScores->GradingPeriod }}</td>
                    <td>{{ $quizScores->UserId }}</td>
                    <td>{{ $quizScores->StudentScore }}</td>
                    <td>{{ $quizScores->TotalScore }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['quizScores.destroy', $quizScores->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('quizScores.show', [$quizScores->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('quizScores.edit', [$quizScores->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $quizScores])
        </div>
    </div>
</div>
