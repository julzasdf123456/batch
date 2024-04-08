<!-- Transactionsid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransactionsId', 'Transactionsid:') !!}
    {!! Form::text('TransactionsId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Particulars Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Particulars', 'Particulars:') !!}
    {!! Form::text('Particulars', null, ['class' => 'form-control', 'maxlength' => 700, 'maxlength' => 700]) !!}
</div>

<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Amount', 'Amount:') !!}
    {!! Form::number('Amount', null, ['class' => 'form-control']) !!}
</div>