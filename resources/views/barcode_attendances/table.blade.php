<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="barcode-attendances-table">
            <thead>
            <tr>
                <th>Studentid</th>
                <th>Punchtype</th>
                <th>Barcodeid</th>
                <th>Smssent</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($barcodeAttendances as $barcodeAttendance)
                <tr>
                    <td>{{ $barcodeAttendance->StudentId }}</td>
                    <td>{{ $barcodeAttendance->PunchType }}</td>
                    <td>{{ $barcodeAttendance->BarcodeId }}</td>
                    <td>{{ $barcodeAttendance->SmsSent }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['barcodeAttendances.destroy', $barcodeAttendance->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('barcodeAttendances.show', [$barcodeAttendance->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('barcodeAttendances.edit', [$barcodeAttendance->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $barcodeAttendances])
        </div>
    </div>
</div>
