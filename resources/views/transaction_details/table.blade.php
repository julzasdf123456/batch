<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="transaction-details-table">
            <thead>
            <tr>
                <th>Transactionsid</th>
                <th>Particulars</th>
                <th>Accountnumber</th>
                <th>Amount</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($transactionDetails as $transactionDetails)
                <tr>
                    <td>{{ $transactionDetails->TransactionsId }}</td>
                    <td>{{ $transactionDetails->Particulars }}</td>
                    <td>{{ $transactionDetails->AccountNumber }}</td>
                    <td>{{ $transactionDetails->Amount }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['transactionDetails.destroy', $transactionDetails->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('transactionDetails.show', [$transactionDetails->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('transactionDetails.edit', [$transactionDetails->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $transactionDetails])
        </div>
    </div>
</div>
