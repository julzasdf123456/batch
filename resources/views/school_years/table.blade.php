<div class="card-header">
    <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Select School Year</span>
</div>
<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover" id="school-years-table">
            <thead>
            <tr>
                <th>School Year</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($schoolYears as $schoolYear)
                <tr>
                    <td>{{ $schoolYear->SchoolYear }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['schoolYears.destroy', $schoolYear->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('schoolYears.show', [$schoolYear->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('schoolYears.edit', [$schoolYear->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $schoolYears])
        </div>
    </div>
</div>
