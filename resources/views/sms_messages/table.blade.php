<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="sms-messages-table">
            <thead>
            <tr>
                <th>Contactnumber</th>
                <th>Message</th>
                <th>Aifacilitator</th>
                <th>Source</th>
                <th>Priority</th>
                <th>Smssent</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($smsMessages as $smsMessages)
                <tr>
                    <td>{{ $smsMessages->ContactNumber }}</td>
                    <td>{{ $smsMessages->Message }}</td>
                    <td>{{ $smsMessages->AIFacilitator }}</td>
                    <td>{{ $smsMessages->Source }}</td>
                    <td>{{ $smsMessages->Priority }}</td>
                    <td>{{ $smsMessages->SmsSent }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['smsMessages.destroy', $smsMessages->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('smsMessages.show', [$smsMessages->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('smsMessages.edit', [$smsMessages->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $smsMessages])
        </div>
    </div>
</div>
