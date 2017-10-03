@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit expense {{ $expense->name }}</div>
                    <div class="panel-body">
                        <a href="{{ url('/expenses') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::model($expense, [
                            'method' => 'PATCH',
                            'url' => ['/expenses', $expense->id],
                            'class' => 'form-horizontal',
                            'files' => true
                        ]) !!}

                        <div class="app_form_container">
                            <div class="form-group">
                                {{ Form::label('name', 'Name') }}
                                {{ Form::text('name', null, array('class' => 'form-control', 'required')) }}
                            </div>

                            <div class="form-group">
                                <label>Payments <a class='btn btn-xs btn-default' href="#" id="add_payment">Add</a></label>

                                @if(count($expense['payments'])<1)
                                    <div id="nopayments">No payments</div>
                                @endif

                                <div id="payments">
                                @foreach($expense['payments'] as $payment)
                                    {!! Form::setModel($payment) !!}
                                    @if(auth()->user()->hasrole('admin') || $payment->status != 'Approved')
                                    <div>
                                        {!! Form::hidden('id', null, array('name' => 'payment['.$payment->id.'][id]')) !!}

                                        <div class="form-group">
                                            {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
                                            <div class="col-sm-10">
                                                {!! Form::text('name', null, array('class' => 'form-control', 'name' => 'payment['.$payment->id.'][name]', 'required' )) !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label('value', 'Value', ['class' => 'col-sm-2 control-label']) !!}
                                            <div class="col-sm-10">
                                                {!! Form::number('value', null, array('class' => 'form-control', 'required', 
                                                    'name' => 'payment['.$payment->id.'][value]', 'step' => '0.01')) !!}
                                                <div class="payment_value" style="display: none;">{{$payment->value}}</div>
                                            </div>
                                        </div>

                                        @role('admin')
                                        <div class="form-group">
                                            {!! Form::label('status', 'Status', ['class' => 'col-sm-2 control-label']) !!}
                                            <div class="col-sm-10">
                                                {!! Form::select('payment['.$payment->id.'][status]', 
                                                ['Un-approved' => 'Un-approved', 'Approved' => 'Approved', 'Rejected' => 'Rejected'], 
                                                $payment->status) !!}
                                            </div>
                                        </div>
                                        @else
                                            {!! Form::hidden('status', null, array('name' => 'payment['.$payment->id.'][status]')) !!}
                                        @endrole

                                        {!! Form::button('Delete', ['class' => 'btn btn-danger btn-xs detele_button', 
                                            'id' => 'payment_delete_'.$payment->id, 'style' => 'float: right;']) !!}

                                        <div style="clear: both; margin-bottom: 15px;"></div>
                                    </div>
                                    @endif
                                @endforeach
                                </div>
                            </div>

                            {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}
                        </div>

                        {!! Form::close() !!}

                        @include('expenses.payment')

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var template = $('#payment-template').html();
        $('#add_payment').on('click', function() {
            if($("#nopayments").length > 0) {
                $("#nopayments").remove();
            }
            var index = $('.js-payment').length;
            $('#payments').append(template.replace(/\{index\}/g, index));
            $('#payment_delete_new_' + index).on('click', function() {
                $(this).parent().remove();
            });
        });

        $('.detele_button').on('click', function() {
            $(this).parent().remove();
        });

        // fix for weird number inputs behavior after validation
        var payments_values = $('.payment_value');
        if(payments_values.length > 0){
            for(var i=0; i<payments_values.length; i++){
                var text = payments_values.eq(i).text();
                payments_values.eq(i).prev().val(text);
            }
        }
    });
</script>
@endsection