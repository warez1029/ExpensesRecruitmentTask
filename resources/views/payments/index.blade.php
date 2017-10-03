@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Payments</div>
                    <div class="panel-body">

                        {!! Form::open(['method' => 'GET', 'url' => '/payments', 'class' => 'navbar-form navbar-right', 'role' => 'search'])  !!}
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                        {!! Form::close() !!}

                        <br/>
                        <br/>
                        <div class="table-responsive" style="width: 100%;">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Name</th><th>Value</th><th>Status</th><th>Expense</th><th>Created</th><th>Updated</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($payments as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->value }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ $item->expense()->first()->name }}</td>
                                        <td>{{ $item->created_at->format('F d, Y h:ia') }}</td>
                                        <td>{{ $item->updated_at->format('F d, Y h:ia') }}</td>
                                        <td>
                                            <a href="{{ url('/payments/' . $item->id) }}" title="View payment"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            @if(auth()->user()->hasrole('admin') || $item->status != 'Approved')
                                            <a href="{{ url('/payments/' . $item->id . '/edit') }}" title="Edit payment"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                            {!! Form::open([
                                                'method'=>'DELETE',
                                                'url' => ['/payments', $item->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn btn-danger btn-xs',
                                                        'title' => 'Delete payment',
                                                        'onclick'=>'return confirm("Confirm delete?")'
                                                )) !!}
                                            {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $payments->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
