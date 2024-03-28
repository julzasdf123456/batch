<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="towns-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Town</th>
                <th>District</th>
                <th>Station</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($towns as $town)
                <tr>
                    <td>{{ $town->id }}</td>
                    <td>{{ $town->Town }}</td>
                    <td>{{ $town->District }}</td>
                    <td>{{ $town->Station }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['towns.destroy', $town->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('towns.show', [$town->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('towns.edit', [$town->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $towns])
        </div>
    </div>
</div>
