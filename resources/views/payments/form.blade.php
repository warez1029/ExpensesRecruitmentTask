<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', 'Name', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('value') ? 'has-error' : ''}}">
    {!! Form::label('value', 'Value', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('value', null, ['class' => 'form-control', 'required']) !!}
        {!! $errors->first('value', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@if(auth()->user()->hasrole('admin'))
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Form::label('status', 'Status', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('status', 
        ['Un-approved' => 'Un-approved', 'Approved' => 'Approved', 'Rejected' => 'Rejected'], 
        $payment->status) !!}
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@endif

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
