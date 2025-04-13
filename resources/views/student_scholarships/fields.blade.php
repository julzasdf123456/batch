<!-- Payableid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PayableId', 'Payableid:') !!}
    {!! Form::text('PayableId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Schoolyear Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SchoolYear', 'Schoolyear:') !!}
    {!! Form::text('SchoolYear', null, ['class' => 'form-control', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- Scholarshipid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ScholarshipId', 'Scholarshipid:') !!}
    {!! Form::text('ScholarshipId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Amount', 'Amount:') !!}
    {!! Form::number('Amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Studentid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('StudentId', 'Studentid:') !!}
    {!! Form::text('StudentId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 1000, 'maxlength' => 1000]) !!}
</div>