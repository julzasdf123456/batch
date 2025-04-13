<!-- Formonth Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ForMonth', 'Formonth:') !!}
    {!! Form::text('ForMonth', null, ['class' => 'form-control','id'=>'ForMonth']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ForMonth').datepicker()
    </script>
@endpush

<!-- Payableid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PayableId', 'Payableid:') !!}
    {!! Form::text('PayableId', null, ['class' => 'form-control', 'maxlength' => 90, 'maxlength' => 90]) !!}
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