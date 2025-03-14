<div class="card-header">
    <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>All Subjects Registered</span>
</div>
<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover" id="subjects-table">
            <thead>
            <tr>
                <th>Subject</th>
                <th>Description</th>
                <th>Parent Subject</th>
                <th class="text-right">Course/Subject Fee</th>
                <th>Teacher/Instructor</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($subjects as $subject)
                <tr>
                    <td>{{ $subject->Subject }}</td>
                    <td>{{ $subject->Description }}</td>
                    <td>{{ $subject->ParentSubject }}</td>
                    <td class="text-right">{{ number_format($subject->CourseFee, 2) }}</td>
                    <td>{{ $subject->Fullname }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['subjects.destroy', $subject->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            @if (Auth::user()->hasAnyPermission(['god permission', 'view subjects']))
                                <a href="{{ route('subjects.show', [$subject->id]) }}"
                                class='btn btn-default btn-xs'>
                                    <i class="far fa-eye"></i>
                                </a>
                            @endif
                            @if (Auth::user()->hasAnyPermission(['god permission', 'edit subjects']))
                                <a href="{{ route('subjects.edit', [$subject->id]) }}"
                                class='btn btn-default btn-xs'>
                                    <i class="far fa-edit"></i>
                                </a>
                            @endif
                            @if (Auth::user()->hasAnyPermission(['god permission', 'delete subjects']))
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
            @include('adminlte-templates::common.paginate', ['records' => $subjects])
        </div>
    </div>
</div>
<div class="card-foooter"></div>
