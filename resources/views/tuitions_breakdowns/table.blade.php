<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="tuitions-breakdowns-table">
            <thead>
            <tr>
                <th>Formonth</th>
                <th>Payableid</th>
                <th>Amountpayable</th>
                <th>Amountpaid</th>
                <th>Balance</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tuitionsBreakdowns as $tuitionsBreakdown)
                <tr>
                    <td>{{ $tuitionsBreakdown->ForMonth }}</td>
                    <td>{{ $tuitionsBreakdown->PayableId }}</td>
                    <td>{{ $tuitionsBreakdown->AmountPayable }}</td>
                    <td>{{ $tuitionsBreakdown->AmountPaid }}</td>
                    <td>{{ $tuitionsBreakdown->Balance }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['tuitionsBreakdowns.destroy', $tuitionsBreakdown->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('tuitionsBreakdowns.show', [$tuitionsBreakdown->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('tuitionsBreakdowns.edit', [$tuitionsBreakdown->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $tuitionsBreakdowns])
        </div>
    </div>
</div>
