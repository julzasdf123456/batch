<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="class-subject-parent-avgs-table">
            <thead>
            <tr>
                <th>Classid</th>
                <th>Parentsubject</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($classSubjectParentAvgs as $classSubjectParentAvg)
                <tr>
                    <td>{{ $classSubjectParentAvg->ClassId }}</td>
                    <td>{{ $classSubjectParentAvg->ParentSubject }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['classSubjectParentAvgs.destroy', $classSubjectParentAvg->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('classSubjectParentAvgs.show', [$classSubjectParentAvg->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('classSubjectParentAvgs.edit', [$classSubjectParentAvg->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $classSubjectParentAvgs])
        </div>
    </div>
</div>
