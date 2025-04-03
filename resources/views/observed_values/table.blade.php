<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="observed-values-table">
            <thead>
            <tr>
                <th>Studentid</th>
                <th>Classid</th>
                <th>Makadiyosone</th>
                <th>Makadiyostwo</th>
                <th>Makataoone</th>
                <th>Makataotwo</th>
                <th>Makakalikasan</th>
                <th>Makabansaone</th>
                <th>Makabansatwo</th>
                <th>Industryone</th>
                <th>Industrytwo</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($observedValues as $observedValues)
                <tr>
                    <td>{{ $observedValues->StudentId }}</td>
                    <td>{{ $observedValues->ClassId }}</td>
                    <td>{{ $observedValues->MakaDiyosOne }}</td>
                    <td>{{ $observedValues->MakaDiyosTwo }}</td>
                    <td>{{ $observedValues->MakaTaoOne }}</td>
                    <td>{{ $observedValues->MakaTaoTwo }}</td>
                    <td>{{ $observedValues->MakaKalikasan }}</td>
                    <td>{{ $observedValues->MakaBansaOne }}</td>
                    <td>{{ $observedValues->MakaBansaTwo }}</td>
                    <td>{{ $observedValues->IndustryOne }}</td>
                    <td>{{ $observedValues->IndustryTwo }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['observedValues.destroy', $observedValues->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('observedValues.show', [$observedValues->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('observedValues.edit', [$observedValues->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $observedValues])
        </div>
    </div>
</div>
