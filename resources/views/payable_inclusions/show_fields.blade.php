<!-- Itemname Field -->
<div class="col-sm-12">
    {!! Form::label('ItemName', 'Itemname:') !!}
    <p>{{ $payableInclusions->ItemName }}</p>
</div>

<!-- Amount Field -->
<div class="col-sm-12">
    {!! Form::label('Amount', 'Amount:') !!}
    <p>{{ $payableInclusions->Amount }}</p>
</div>

<!-- Payableid Field -->
<div class="col-sm-12">
    {!! Form::label('PayableId', 'Payableid:') !!}
    <p>{{ $payableInclusions->PayableId }}</p>
</div>

