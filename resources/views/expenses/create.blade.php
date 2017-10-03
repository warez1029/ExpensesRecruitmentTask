@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Create expense</div>
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

                        {!! Form::open(['url' => '/expenses', 'class' => 'form-horizontal']) !!}

                        <div class="app_form_container">
                            <div class="form-group">
                                {{ Form::label('name', 'Name') }}
                                {{ Form::text('name', '', array('class' => 'form-control', 'required')) }}
                            </div>

                            <div class="form-group">
                                <label>Payments <a class='btn btn-xs btn-default' href="#" id="add_payment">Add</a></label>
                                <div id="nopayments">No payments</div>
                                <div id="payments"></div>
                            </div>

                            {{ Form::submit('Create', array('class' => 'btn btn-primary')) }}
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
        });
    });
</script>
@endsection