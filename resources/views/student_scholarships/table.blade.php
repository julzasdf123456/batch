<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="student-scholarships-table">
            <thead>
            <tr>
                <th>Payableid</th>
                <th>Schoolyear</th>
                <th>Scholarshipid</th>
                <th>Amount</th>
                <th>Studentid</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($studentScholarships as $studentScholarships)
                <tr>
                    <td>{{ $studentScholarships->PayableId }}</td>
                    <td>{{ $studentScholarships->SchoolYear }}</td>
                    <td>{{ $studentScholarships->ScholarshipId }}</td>
                    <td>{{ $studentScholarships->Amount }}</td>
                    <td>{{ $studentScholarships->StudentId }}</td>
                    <td>{{ $studentScholarships->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['studentScholarships.destroy', $studentScholarships->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('studentScholarships.show', [$studentScholarships->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('studentScholarships.edit', [$studentScholarships->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $studentScholarships])
        </div>
    </div>
</div>
