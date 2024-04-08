<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="payables-table">
            <thead>
            <tr>
                <th>Studentid</th>
                <th>Paymentfor</th>
                <th>Amountpayable</th>
                <th>Amountpaid</th>
                <th>Balance</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($payables as $payables)
                <tr>
                    <td>{{ $payables->StudentId }}</td>
                    <td>{{ $payables->PaymentFor }}</td>
                    <td>{{ $payables->AmountPayable }}</td>
                    <td>{{ $payables->AmountPaid }}</td>
                    <td>{{ $payables->Balance }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['payables.destroy', $payables->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('payables.show', [$payables->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('payables.edit', [$payables->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $payables])
        </div>
    </div>
</div>
