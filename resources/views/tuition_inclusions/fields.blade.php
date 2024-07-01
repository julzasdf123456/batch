<!-- Itemname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ItemName', 'Item Name:') !!}
    {!! Form::text('ItemName', null, ['class' => 'form-control', 'maxlength' => 1000, 'maxlength' => 1000]) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Amount', 'Amount:') !!}
    {!! Form::number('Amount', null, ['class' => 'form-control']) !!}
</div>