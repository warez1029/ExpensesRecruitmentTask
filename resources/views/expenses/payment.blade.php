<script id="payment-template" type="text/x-custom-template">
<div>
    <div class="form-group js-payment">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name{index}', null, array('class' => 'form-control', 'required', 'name' => 'payment[new_{index}][name]' )) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('value', 'Value', ['class' => 'col-sm-2 control-label']) !!}
         <div class="col-sm-10">
             {!! Form::number('value{index}', null, array('class' => 'form-control', 'name' => 'payment[new_{index}][value]', 'required', 'step' => '0.01')) !!}
         </div>
    </div>

    @role('admin')
    <div class="form-group">
        {!! Form::label('status', 'Status', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('payment[new_{index}][status]', 
            ['Un-approved' => 'Un-approved', 'Approved' => 'Approved', 'Rejected' => 'Rejected'], 
            'Un-approved') !!}
        </div>
    </div>
    @endrole

    {!! Form::button('Delete', ['class' => 'btn btn-danger btn-xs detele_button', 
        'id' => 'payment_delete_new_{index}', 'style' => 'float: right;']) !!}

    <div style="clear: both; margin-bottom: 15px;"></div>
</div>
</script>