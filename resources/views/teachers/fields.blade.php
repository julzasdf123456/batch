<!-- Fullname Field -->
<div class="form-group col-lg-12">
    {!! Form::label('FullName', "Teacher's Full Name:") !!}
    {!! Form::text('FullName', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500, 'placeholder' => "Teacher's full name...", 'autofocus' => true, 'required' => true]) !!}
</div>

<!-- Designation Field -->
<div class="form-group col-lg-12">
    {!! Form::label('Designation', 'Designation:') !!}
    {!! Form::text('Designation', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50, 'placeholder' => 'School position/designation...', 'required' => true]) !!}
</div>

<!-- Subjectexpertise Field -->
<div class="form-group col-lg-12">
    {!! Form::label('SubjectExpertise', 'Subject Expertise:') !!}
    {!! Form::text('SubjectExpertise', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500, 'placeholder' => 'Separate by comma if many...', 'required' => true]) !!}
</div>

<!-- Department Field -->
<div class="form-group col-lg-12">
    {!! Form::label('Department', 'Department:') !!}
    {!! Form::select('Department', ['Grade School' => 'Grade School', 'High School' => 'High School'], null, ['class' => 'form-control', 'required' => true]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-lg-12">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::select('Status', ['ACTIVE' => 'Actively Teaching', 'RESIGNED' => 'Resigned'], null, ['class' => 'form-control', 'required' => true]) !!}
</div>