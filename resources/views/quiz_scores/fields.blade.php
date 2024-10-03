<!-- Studentid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('StudentId', 'Studentid:') !!}
    {!! Form::text('StudentId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Subjectid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SubjectId', 'Subjectid:') !!}
    {!! Form::text('SubjectId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Classid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ClassId', 'Classid:') !!}
    {!! Form::text('ClassId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Teacherid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TeacherId', 'Teacherid:') !!}
    {!! Form::text('TeacherId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Gradingperiod Field -->
<div class="form-group col-sm-6">
    {!! Form::label('GradingPeriod', 'Gradingperiod:') !!}
    {!! Form::text('GradingPeriod', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Studentscore Field -->
<div class="form-group col-sm-6">
    {!! Form::label('StudentScore', 'Studentscore:') !!}
    {!! Form::number('StudentScore', null, ['class' => 'form-control']) !!}
</div>

<!-- Totalscore Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TotalScore', 'Totalscore:') !!}
    {!! Form::number('TotalScore', null, ['class' => 'form-control']) !!}
</div>