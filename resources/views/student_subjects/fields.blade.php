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

<!-- Firstgradinggrade Field -->
<div class="form-group col-sm-6">
    {!! Form::label('FirstGradingGrade', 'Firstgradinggrade:') !!}
    {!! Form::number('FirstGradingGrade', null, ['class' => 'form-control']) !!}
</div>

<!-- Secondgradinggrade Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SecondGradingGrade', 'Secondgradinggrade:') !!}
    {!! Form::number('SecondGradingGrade', null, ['class' => 'form-control']) !!}
</div>

<!-- Thirdgradinggrade Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ThirdGradingGrade', 'Thirdgradinggrade:') !!}
    {!! Form::number('ThirdGradingGrade', null, ['class' => 'form-control']) !!}
</div>

<!-- Fourthgradinggrade Field -->
<div class="form-group col-sm-6">
    {!! Form::label('FourthGradingGrade', 'Fourthgradinggrade:') !!}
    {!! Form::number('FourthGradingGrade', null, ['class' => 'form-control']) !!}
</div>

<!-- Averagegrade Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AverageGrade', 'Averagegrade:') !!}
    {!! Form::number('AverageGrade', null, ['class' => 'form-control']) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 1000, 'maxlength' => 1000]) !!}
</div>