<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="tuition-inclusions-table">
            <thead>
            <tr>
                <th>Itemname</th>
                <th>Amount</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tuitionInclusions as $tuitionInclusions)
                <tr>
                    <td>{{ $tuitionInclusions->ItemName }}</td>
                    <td>{{ $tuitionInclusions->Amount }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['tuitionInclusions.destroy', $tuitionInclusions->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('tuitionInclusions.show', [$tuitionInclusions->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('tuitionInclusions.edit', [$tuitionInclusions->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $tuitionInclusions])
        </div>
    </div>
</div>
