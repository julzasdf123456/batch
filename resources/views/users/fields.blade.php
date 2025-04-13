<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required', 'maxlength' => 255, 'maxlength' => 255]) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control', 'required', 'maxlength' => 255, 'maxlength' => 255]) !!}
</div>

<!-- Username Field -->
<div class="form-group col-sm-6">
    {!! Form::label('username', 'Username:') !!}
    {!! Form::text('username', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<div class="form-group col-sm-6">
    <label for="TeacherId">Parse User to Teacher</label>
    <select name="TeacherId" id="TeacherId" class="custom-select select2">
        <option value="">-- Select --</option>
        @foreach ($teachers as $item)
            <option value="{{ $item->id }}">{{ $item->FullName }}</option>
        @endforeach
    </select>
</div>
