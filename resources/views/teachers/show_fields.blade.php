<!-- Fullname Field -->
<div class="col-sm-12">
    {!! Form::label('FullName', 'Fullname:') !!}
    <p>{{ $teachers->FullName }}</p>
</div>

<!-- Designation Field -->
<div class="col-sm-12">
    {!! Form::label('Designation', 'Designation:') !!}
    <p>{{ $teachers->Designation }}</p>
</div>

<!-- Subjectexpertise Field -->
<div class="col-sm-12">
    {!! Form::label('SubjectExpertise', 'Subjectexpertise:') !!}
    <p>{{ $teachers->SubjectExpertise }}</p>
</div>

<!-- Department Field -->
<div class="col-sm-12">
    {!! Form::label('Department', 'Department:') !!}
    <p>{{ $teachers->Department }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-12">
    {!! Form::label('Status', 'Status:') !!}
    <p>{{ $teachers->Status }}</p>
</div>

