<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="payable-inclusions-table">
            <thead>
            <tr>
                <th>Itemname</th>
                <th>Amount</th>
                <th>Payableid</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($payableInclusions as $payableInclusions)
                <tr>
                    <td>{{ $payableInclusions->ItemName }}</td>
                    <td>{{ $payableInclusions->Amount }}</td>
                    <td>{{ $payableInclusions->PayableId }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['payableInclusions.destroy', $payableInclusions->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('payableInclusions.show', [$payableInclusions->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('payableInclusions.edit', [$payableInclusions->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $payableInclusions])
        </div>
    </div>
</div>
