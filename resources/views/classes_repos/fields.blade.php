<!-- Year Field -->
<div class="form-group col-lg-12">
    {!! Form::label('Year', 'Year Level:') !!}
    {!! Form::select('Year', [
        'Grade 7' => 'Grade 7', 
        'Grade 8' => 'Grade 8', 
        'Grade 9' => 'Grade 9', 
        'Grade 10' => 'Grade 10', 
        'Grade 11' => 'Grade 11', 
        'Grade 12' => 'Grade 12'], null, ['class' => 'form-control']) !!}
</div>

<!-- Section Field -->
<div class="form-group col-lg-12">
    {!! Form::label('Section', 'Section Name:') !!}
    {!! Form::text('Section', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50, 'placeholder' => 'Section name...']) !!}
</div>