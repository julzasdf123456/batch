<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="transactions-table">
            <thead>
            <tr>
                <th>Payablesid</th>
                <th>Studentid</th>
                <th>Paymentfor</th>
                <th>Modeofpayment</th>
                <th>Ornumber</th>
                <th>Ordate</th>
                <th>Cashamount</th>
                <th>Checkamount</th>
                <th>Digitalpaymentamount</th>
                <th>Totalamountpaid</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->PayablesId }}</td>
                    <td>{{ $transaction->StudentId }}</td>
                    <td>{{ $transaction->PaymentFor }}</td>
                    <td>{{ $transaction->ModeOfPayment }}</td>
                    <td>{{ $transaction->ORNumber }}</td>
                    <td>{{ $transaction->ORDate }}</td>
                    <td>{{ $transaction->CashAmount }}</td>
                    <td>{{ $transaction->CheckAmount }}</td>
                    <td>{{ $transaction->DigitalPaymentAmount }}</td>
                    <td>{{ $transaction->TotalAmountPaid }}</td>
                    <td>{{ $transaction->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['transactions.destroy', $transaction->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('transactions.show', [$transaction->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('transactions.edit', [$transaction->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $transactions])
        </div>
    </div>
</div>
