<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="barangays-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Barangay</th>
                <th>Townid</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($barangays as $barangay)
                <tr>
                    <td>{{ $barangay->id }}</td>
                    <td>{{ $barangay->Barangay }}</td>
                    <td>{{ $barangay->TownId }}</td>
                    <td>{{ $barangay->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['barangays.destroy', $barangay->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('barangays.show', [$barangay->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('barangays.edit', [$barangay->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $barangays])
        </div>
    </div>
</div>
