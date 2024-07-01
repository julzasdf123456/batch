<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="miscellaneous-payables-table">
            <thead>
            <tr>
                <th>Payable</th>
                <th>Defaultamount</th>
                <th>Schoolyear</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($miscellaneousPayables as $item)
                <tr>
                    <td>{{ $item->Payable }}</td>
                    <td>{{ $item->DefaultAmount }}</td>
                    <td>{{ $item->SchoolYear }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['miscellaneousPayables.destroy', $item->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('miscellaneousPayables.show', [$item->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('miscellaneousPayables.edit', [$item->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $miscellaneousPayables])
        </div>
    </div>
</div>
