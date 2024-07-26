<div class="card-header">
    <span class="card-title"><i class="fas fa-list ico-tab"></i>List of Supported Scholarship Grants</span>
</div>
<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover" id="scholarships-table">
            <thead>
            <tr>
                <th>Scholarship</th>
                <th>Notes</th>
                <th>Percentage</th>
                <th>Amount</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($scholarships as $item)
                <tr>
                    <td>{{ $item->Scholarship }}</td>
                    <td>{{ $item->Notes }}</td>
                    <td>{{ $item->Percentage }}</td>
                    <td>{{ $item->Amount }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['scholarships.destroy', $item->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            
                            @if (Auth::user()->hasAnyPermission(['god permission', 'view scholarship']))
                            <a href="{{ route('scholarships.show', [$item->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            @endif
                            @if (Auth::user()->hasAnyPermission(['god permission', 'edit scholarship']))
                            <a href="{{ route('scholarships.edit', [$item->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            @endif
                            @if (Auth::user()->hasAnyPermission(['god permission', 'delete scholarship']))
                                {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            @endif
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
            @include('adminlte-templates::common.paginate', ['records' => $scholarships])
        </div>
    </div>
</div>
