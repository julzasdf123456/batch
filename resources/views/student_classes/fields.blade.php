<!-- Classid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ClassId', 'Classid:') !!}
    {!! Form::text('ClassId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Studentid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('StudentId', 'Studentid:') !!}
    {!! Form::text('StudentId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Enrollmentornumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EnrollmentORNumber', 'Enrollmentornumber:') !!}
    {!! Form::text('EnrollmentORNumber', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Enrollmentordate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EnrollmentORDate', 'Enrollmentordate:') !!}
    {!! Form::text('EnrollmentORDate', null, ['class' => 'form-control','id'=>'EnrollmentORDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#EnrollmentORDate').datepicker()
    </script>
@endpush