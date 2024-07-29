<!-- Year Field -->
<div class="form-group col-lg-12">
    {!! Form::label('Year', 'Year Level:') !!}
    {!! Form::select('Year', [
        'Grade 7' => 'Grade 7', 
        'Grade 8' => 'Grade 8', 
        'Grade 9' => 'Grade 9', 
        'Grade 10' => 'Grade 10', 
        'Grade 11' => 'Grade 11', 
        'Grade 12' => 'Grade 12'], null, ['class' => 'form-control']) !!}
</div>

<!-- Section Field -->
<div class="form-group col-lg-12">
    {!! Form::label('Section', 'Section Name:') !!}
    {!! Form::text('Section', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50, 'placeholder' => 'Section name...']) !!}
</div>

<!-- Strand Field -->
<div class="form-group col-lg-12">
    {!! Form::label('Strand', 'Track & Strand:') !!}
    {!! Form::text('Strand', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500, 'placeholder' => 'Strand name...']) !!}
</div>

<!-- SEmester Field -->
<div class="form-group col-lg-12">
    {!! Form::label('Semester', 'Semester:') !!}
    {!! Form::select('Semester', [
        '' => 'Not Applicable', 
        '1st' => '1st Semester', 
        '2nd' => '2nd Semester'], null, ['class' => 'form-control']) !!}
</div>

<!-- Teachers Field -->
<div class="form-group col-lg-12">
    {!! Form::label('Adviser', 'Teacher Adviser:') !!}
    {!! Form::select('Adviser', $teachers, null, ['class' => 'form-control',]) !!}
</div>

<!-- Tuition Field -->
<div class="form-group col-lg-12">
    {!! Form::label('BaseTuitionFee', 'Tuition Fee (From Private Schools, Default):') !!}
    {!! Form::number('BaseTuitionFee', null, ['class' => 'form-control', 'step' => 'any']) !!}
</div>

<!-- BaseTuitionFeePublic Field -->
<div class="form-group col-lg-12">
    {!! Form::label('BaseTuitionFeePublic', 'Tuition Fee (From Public Schools):') !!}
    {!! Form::number('BaseTuitionFeePublic', null, ['class' => 'form-control', 'step' => 'any']) !!}
</div>