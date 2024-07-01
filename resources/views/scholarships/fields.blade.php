<!-- Scholarship Field -->
<div class="form-group col-lg-12">
    {!! Form::label('Scholarship', 'Scholarship Grant:') !!}
    {!! Form::text('Scholarship', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-lg-12">
    {!! Form::label('Notes', 'Description:') !!}
    {!! Form::textarea('Notes', null, ['class' => 'form-control', 'rows' => 3]) !!}
</div>

<div class="col-lg-12">
    <h4><strong>Scholarship Discount Type</strong></h4>
</div>

<!-- Amount Field -->
<div class="form-group col-lg-6">
    {!! Form::label('Amount', 'By Amount (Priority):') !!}
    {!! Form::number('Amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Percentage Field -->
<div class="form-group col-lg-6">
    {!! Form::label('Percentage', 'By Tuition Fee Percentage (in decimal - e.g., 100%=1, 50%=0.5):') !!}
    {!! Form::number('Percentage', null, ['class' => 'form-control', 'step' => 'any']) !!}
</div>

<div class="col-lg-12">
    <p class="no-pads text-muted"><strong>NOTE</strong> that the system will only select <strong>1 (one)</strong> discount type that will be deducted to the tuition fees. 
        Should you happen to input <strong>BOTH</strong> percentage and amount, the system <strong>WILL ALWAYS PRIORITIZE THE AMOUNT</strong> over the percentage.
    </p>
</div>