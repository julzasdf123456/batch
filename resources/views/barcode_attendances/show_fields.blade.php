<!-- Studentid Field -->
<div class="col-sm-12">
    {!! Form::label('StudentId', 'Studentid:') !!}
    <p>{{ $barcodeAttendance->StudentId }}</p>
</div>

<!-- Punchtype Field -->
<div class="col-sm-12">
    {!! Form::label('PunchType', 'Punchtype:') !!}
    <p>{{ $barcodeAttendance->PunchType }}</p>
</div>

<!-- Barcodeid Field -->
<div class="col-sm-12">
    {!! Form::label('BarcodeId', 'Barcodeid:') !!}
    <p>{{ $barcodeAttendance->BarcodeId }}</p>
</div>

<!-- Smssent Field -->
<div class="col-sm-12">
    {!! Form::label('SmsSent', 'Smssent:') !!}
    <p>{{ $barcodeAttendance->SmsSent }}</p>
</div>

