<!-- Itemname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ItemName', 'Itemname:') !!}
    {!! Form::text('ItemName', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Amount', 'Amount:') !!}
    {!! Form::number('Amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Payableid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PayableId', 'Payableid:') !!}
    {!! Form::text('PayableId', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>