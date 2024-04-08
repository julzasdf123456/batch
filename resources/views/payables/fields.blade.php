<!-- Studentid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('StudentId', 'Studentid:') !!}
    {!! Form::text('StudentId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Paymentfor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PaymentFor', 'Paymentfor:') !!}
    {!! Form::text('PaymentFor', null, ['class' => 'form-control', 'maxlength' => 1000, 'maxlength' => 1000]) !!}
</div>

<!-- Amountpayable Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AmountPayable', 'Amountpayable:') !!}
    {!! Form::number('AmountPayable', null, ['class' => 'form-control']) !!}
</div>

<!-- Amountpaid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AmountPaid', 'Amountpaid:') !!}
    {!! Form::number('AmountPaid', null, ['class' => 'form-control']) !!}
</div>

<!-- Balance Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Balance', 'Balance:') !!}
    {!! Form::number('Balance', null, ['class' => 'form-control']) !!}
</div>