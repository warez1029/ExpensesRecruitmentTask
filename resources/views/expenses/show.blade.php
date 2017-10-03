@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Expense {{ $expense->name }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/expenses') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/expenses/' . $expense->id . '/edit') }}" title="Edit expense"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        @if(auth()->user()->hasrole('admin') || !$expense->payments()->where('status', '=', 'Approved')->exists())
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['expenses', $expense->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete expense',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ))!!}
                        {!! Form::close() !!}
                        @endif
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr><th> Name </th><td> {{ $expense->name }} </td></tr>
                                    <tr><th> Payments </th><td> 
                                    @foreach($expense->payments as $item)
                                        <div style="width: 100%;">
                                        <b>Name:</b> {{$item->name}}, 
                                        <b>Value:</b> {{$item->value}}, 
                                        <b>Status:</b> {{$item->status}}</div>
                                    @endforeach
                                    </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
