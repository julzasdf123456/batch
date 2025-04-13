<!-- Contactnumber Field -->
<div class="col-sm-12">
    {!! Form::label('ContactNumber', 'Contactnumber:') !!}
    <p>{{ $smsMessages->ContactNumber }}</p>
</div>

<!-- Message Field -->
<div class="col-sm-12">
    {!! Form::label('Message', 'Message:') !!}
    <p>{{ $smsMessages->Message }}</p>
</div>

<!-- Aifacilitator Field -->
<div class="col-sm-12">
    {!! Form::label('AIFacilitator', 'Aifacilitator:') !!}
    <p>{{ $smsMessages->AIFacilitator }}</p>
</div>

<!-- Source Field -->
<div class="col-sm-12">
    {!! Form::label('Source', 'Source:') !!}
    <p>{{ $smsMessages->Source }}</p>
</div>

<!-- Priority Field -->
<div class="col-sm-12">
    {!! Form::label('Priority', 'Priority:') !!}
    <p>{{ $smsMessages->Priority }}</p>
</div>

<!-- Smssent Field -->
<div class="col-sm-12">
    {!! Form::label('SmsSent', 'Smssent:') !!}
    <p>{{ $smsMessages->SmsSent }}</p>
</div>

