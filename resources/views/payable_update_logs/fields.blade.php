<!-- Payableid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PayableId', 'Payableid:') !!}
    {!! Form::text('PayableId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Ogtotalpayable Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OGTotalPayable', 'Ogtotalpayable:') !!}
    {!! Form::number('OGTotalPayable', null, ['class' => 'form-control']) !!}
</div>

<!-- Ogpaidamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OGPaidAmount', 'Ogpaidamount:') !!}
    {!! Form::number('OGPaidAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Ogbalance Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OGBalance', 'Ogbalance:') !!}
    {!! Form::number('OGBalance', null, ['class' => 'form-control']) !!}
</div>

<!-- Newtotalpayable Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewTotalPayable', 'Newtotalpayable:') !!}
    {!! Form::number('NewTotalPayable', null, ['class' => 'form-control']) !!}
</div>

<!-- Newpaidamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewPaidAmount', 'Newpaidamount:') !!}
    {!! Form::number('NewPaidAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Newbalance Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewBalance', 'Newbalance:') !!}
    {!! Form::number('NewBalance', null, ['class' => 'form-control']) !!}
</div>