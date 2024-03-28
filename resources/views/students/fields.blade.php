<!-- Firstname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('FirstName', 'Firstname:') !!}
    {!! Form::text('FirstName', null, ['class' => 'form-control', 'maxlength' => 60, 'maxlength' => 60]) !!}
</div>

<!-- Middlename Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MiddleName', 'Middlename:') !!}
    {!! Form::text('MiddleName', null, ['class' => 'form-control', 'maxlength' => 60, 'maxlength' => 60]) !!}
</div>

<!-- Lastname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LastName', 'Lastname:') !!}
    {!! Form::text('LastName', null, ['class' => 'form-control', 'maxlength' => 60, 'maxlength' => 60]) !!}
</div>

<!-- Suffix Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Suffix', 'Suffix:') !!}
    {!! Form::text('Suffix', null, ['class' => 'form-control', 'maxlength' => 60, 'maxlength' => 60]) !!}
</div>

<!-- Birthdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Birthdate', 'Birthdate:') !!}
    {!! Form::text('Birthdate', null, ['class' => 'form-control','id'=>'Birthdate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#Birthdate').datepicker()
    </script>
@endpush

<!-- Gender Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Gender', 'Gender:') !!}
    {!! Form::text('Gender', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Sitio Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Sitio', 'Sitio:') !!}
    {!! Form::text('Sitio', null, ['class' => 'form-control', 'maxlength' => 60, 'maxlength' => 60]) !!}
</div>

<!-- Barangay Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Barangay', 'Barangay:') !!}
    {!! Form::text('Barangay', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Town Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Town', 'Town:') !!}
    {!! Form::text('Town', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Contactnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ContactNumber', 'Contactnumber:') !!}
    {!! Form::text('ContactNumber', null, ['class' => 'form-control', 'maxlength' => 90, 'maxlength' => 90]) !!}
</div>