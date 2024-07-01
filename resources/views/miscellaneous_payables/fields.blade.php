<!-- Payable Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Payable', 'Payable Name:') !!}
    {!! Form::text('Payable', null, ['class' => 'form-control', 'maxlength' => 600, 'maxlength' => 600]) !!}
</div>

<!-- Defaultamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DefaultAmount', 'Default Amount:') !!}
    {!! Form::number('DefaultAmount', null, ['class' => 'form-control']) !!}
</div>
