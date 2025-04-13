<!-- Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id', 'Id:') !!}
    {!! Form::text('id', null, ['class' => 'form-control']) !!}
</div>

<!-- Town Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Town', 'Town:') !!}
    {!! Form::text('Town', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>

<!-- District Field -->
<div class="form-group col-sm-6">
    {!! Form::label('District', 'District:') !!}
    {!! Form::text('District', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Station Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Station', 'Station:') !!}
    {!! Form::text('Station', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>