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
            @foreach($transactions as $transactions)
                <tr>
                    <td>{{ $transactions->PayablesId }}</td>
                    <td>{{ $transactions->StudentId }}</td>
                    <td>{{ $transactions->PaymentFor }}</td>
                    <td>{{ $transactions->ModeOfPayment }}</td>
                    <td>{{ $transactions->ORNumber }}</td>
                    <td>{{ $transactions->ORDate }}</td>
                    <td>{{ $transactions->CashAmount }}</td>
                    <td>{{ $transactions->CheckAmount }}</td>
                    <td>{{ $transactions->DigitalPaymentAmount }}</td>
                    <td>{{ $transactions->TotalAmountPaid }}</td>
                    <td>{{ $transactions->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['transactions.destroy', $transactions->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('transactions.show', [$transactions->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('transactions.edit', [$transactions->id]) }}"
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
