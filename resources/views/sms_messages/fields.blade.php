<!-- Contactnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ContactNumber', 'Contactnumber:') !!}
    {!! Form::text('ContactNumber', null, ['class' => 'form-control', 'maxlength' => 60, 'maxlength' => 60]) !!}
</div>

<!-- Message Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Message', 'Message:') !!}
    {!! Form::text('Message', null, ['class' => 'form-control', 'maxlength' => 1500, 'maxlength' => 1500]) !!}
</div>

<!-- Aifacilitator Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AIFacilitator', 'Aifacilitator:') !!}
    {!! Form::text('AIFacilitator', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Source Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Source', 'Source:') !!}
    {!! Form::text('Source', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Priority Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Priority', 'Priority:') !!}
    {!! Form::number('Priority', null, ['class' => 'form-control']) !!}
</div>

<!-- Smssent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SmsSent', 'Smssent:') !!}
    {!! Form::text('SmsSent', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>