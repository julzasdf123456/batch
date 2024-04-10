<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover" id="classes-repos-table">
            <thead>
            <tr>
                <th>Year</th>
                <th>Section</th>
                <th>Adviser</th>
                <th class="text-right">Tuition Fees</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($classesRepos as $classesRepo)
                <tr>
                    <td>{{ $classesRepo->Year }}</td>
                    <td>{{ $classesRepo->Section }}</td>
                    <td>{{ $classesRepo->FullName }}</td>
                    <td class="text-right">{{ number_format($classesRepo->BaseTuitionFee, 2) }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['classesRepos.destroy', $classesRepo->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('classesRepos.show', [$classesRepo->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('classesRepos.edit', [$classesRepo->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $classesRepos])
        </div>
    </div>
</div>
