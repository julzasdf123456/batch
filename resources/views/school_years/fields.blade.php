<!-- Schoolyear Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SchoolYear', 'School Year:') !!}
    {!! Form::text('SchoolYear', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50, 'required' => true]) !!}
</div>

<!-- MonthStart Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MonthStart', 'Class Start Date:') !!}
    <input type="date" class="form-control" name="MonthStart" id="MonthStart" required value="{{ isset($schoolYear) && $schoolYear->MonthStart != null ? $schoolYear->MonthStart : null }}">
</div>