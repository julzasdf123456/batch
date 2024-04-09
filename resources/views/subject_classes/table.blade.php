<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="subject-classes-table">
            <thead>
            <tr>
                <th>Subjectid</th>
                <th>Classrepoid</th>
                <th>Userid</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($subjectClasses as $subjectClass)
                <tr>
                    <td>{{ $subjectClass->SubjectId }}</td>
                    <td>{{ $subjectClass->ClassRepoId }}</td>
                    <td>{{ $subjectClass->UserId }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['subjectClasses.destroy', $subjectClass->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('subjectClasses.show', [$subjectClass->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('subjectClasses.edit', [$subjectClass->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $subjectClasses])
        </div>
    </div>
</div>
