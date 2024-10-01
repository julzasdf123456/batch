<!-- Subject Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Subject', 'Subject:') !!}
    {!! Form::text('Subject', null, ['class' => 'form-control', 'maxlength' => 100, 'maxlength' => 100, 'autofocus' => true]) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Description', 'Description:') !!}
    {!! Form::text('Description', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CourseFee', 'Course/Subject Fee:') !!}
    {!! Form::number('CourseFee', null, ['class' => 'form-control', 'step' =>'any']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Teacher', 'Teacher/Instructor:') !!}
    {!! Form::select('Teacher', $teachers, null, ['class' => 'form-control',]) !!}
</div>

<!-- ParentSubject Field -->
<div class="form-group col-lg-6">
    {!! Form::label('ParentSubject', 'Parent Subject:') !!}
    {!! Form::select('ParentSubject', ['' => 'None', 'MAPEH' => 'MAPEH', 'TLE' => 'TLE', 'Special Sciences' => 'Special Sciences'], null, ['class' => 'form-control']) !!}
</div>