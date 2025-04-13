<!-- Studentid Field -->
<div class="col-sm-12">
    {!! Form::label('StudentId', 'Studentid:') !!}
    <p>{{ $payables->StudentId }}</p>
</div>

<!-- Paymentfor Field -->
<div class="col-sm-12">
    {!! Form::label('PaymentFor', 'Paymentfor:') !!}
    <p>{{ $payables->PaymentFor }}</p>
</div>

<!-- Amountpayable Field -->
<div class="col-sm-12">
    {!! Form::label('AmountPayable', 'Amountpayable:') !!}
    <p>{{ $payables->AmountPayable }}</p>
</div>

<!-- Amountpaid Field -->
<div class="col-sm-12">
    {!! Form::label('AmountPaid', 'Amountpaid:') !!}
    <p>{{ $payables->AmountPaid }}</p>
</div>

<!-- Balance Field -->
<div class="col-sm-12">
    {!! Form::label('Balance', 'Balance:') !!}
    <p>{{ $payables->Balance }}</p>
</div>

