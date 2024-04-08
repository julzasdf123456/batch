<!-- Payablesid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PayablesId', 'Payablesid:') !!}
    {!! Form::text('PayablesId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Studentid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('StudentId', 'Studentid:') !!}
    {!! Form::text('StudentId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Paymentfor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PaymentFor', 'Paymentfor:') !!}
    {!! Form::text('PaymentFor', null, ['class' => 'form-control', 'maxlength' => 1000, 'maxlength' => 1000]) !!}
</div>

<!-- Modeofpayment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ModeOfPayment', 'Modeofpayment:') !!}
    {!! Form::text('ModeOfPayment', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Ornumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ORNumber', 'Ornumber:') !!}
    {!! Form::text('ORNumber', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Ordate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ORDate', 'Ordate:') !!}
    {!! Form::text('ORDate', null, ['class' => 'form-control','id'=>'ORDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ORDate').datepicker()
    </script>
@endpush

<!-- Cashamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CashAmount', 'Cashamount:') !!}
    {!! Form::number('CashAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Checkamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CheckAmount', 'Checkamount:') !!}
    {!! Form::number('CheckAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Digitalpaymentamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DigitalPaymentAmount', 'Digitalpaymentamount:') !!}
    {!! Form::number('DigitalPaymentAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Totalamountpaid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TotalAmountPaid', 'Totalamountpaid:') !!}
    {!! Form::number('TotalAmountPaid', null, ['class' => 'form-control']) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 1050, 'maxlength' => 1050]) !!}
</div>