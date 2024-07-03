<!-- Studentid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('StudentId', 'Studentid:') !!}
    {!! Form::text('StudentId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Punchtype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PunchType', 'Punchtype:') !!}
    {!! Form::text('PunchType', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Barcodeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BarcodeId', 'Barcodeid:') !!}
    {!! Form::text('BarcodeId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Smssent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SmsSent', 'Smssent:') !!}
    {!! Form::text('SmsSent', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>