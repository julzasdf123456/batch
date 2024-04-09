<!-- Subject Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Subject', 'Subject:') !!}
    {!! Form::text('Subject', null, ['class' => 'form-control', 'maxlength' => 100, 'maxlength' => 100, 'autofocus' => true]) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Description', 'Description:') !!}
    {!! Form::text('Description', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>