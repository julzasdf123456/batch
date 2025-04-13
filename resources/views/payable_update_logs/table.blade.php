<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="payable-update-logs-table">
            <thead>
            <tr>
                <th>Payableid</th>
                <th>Userid</th>
                <th>Ogtotalpayable</th>
                <th>Ogpaidamount</th>
                <th>Ogbalance</th>
                <th>Newtotalpayable</th>
                <th>Newpaidamount</th>
                <th>Newbalance</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($payableUpdateLogs as $payableUpdateLogs)
                <tr>
                    <td>{{ $payableUpdateLogs->PayableId }}</td>
                    <td>{{ $payableUpdateLogs->UserId }}</td>
                    <td>{{ $payableUpdateLogs->OGTotalPayable }}</td>
                    <td>{{ $payableUpdateLogs->OGPaidAmount }}</td>
                    <td>{{ $payableUpdateLogs->OGBalance }}</td>
                    <td>{{ $payableUpdateLogs->NewTotalPayable }}</td>
                    <td>{{ $payableUpdateLogs->NewPaidAmount }}</td>
                    <td>{{ $payableUpdateLogs->NewBalance }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['payableUpdateLogs.destroy', $payableUpdateLogs->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('payableUpdateLogs.show', [$payableUpdateLogs->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('payableUpdateLogs.edit', [$payableUpdateLogs->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $payableUpdateLogs])
        </div>
    </div>
</div>
