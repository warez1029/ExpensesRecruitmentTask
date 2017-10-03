@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Expenses</div>
                    <div class="panel-body">
                        <a href="{{ url('/expenses/create') }}" class="btn btn-success btn-sm" title="Add New expense">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        {!! Form::open(['method' => 'GET', 'url' => '/expenses', 'class' => 'navbar-form navbar-right', 'role' => 'search'])  !!}
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
                                        <th>Name</th><th>Created</th><th>Updated</th><th>Payments</th><th>Summary</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($expenses as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->created_at->format('F d, Y h:ia') }}</td>
                                        <td>{{ $item->updated_at->format('F d, Y h:ia') }}</td>
                                        <td>{{ $item->payments->count() }}</td>
                                        <td>{{ $item->payments->sum('value') }}</td>
                                        <td>
                                            <a href="{{ url('/expenses/' . $item->id) }}" title="View expense"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/expenses/' . $item->id . '/edit') }}" title="Edit expense"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                            @if(auth()->user()->hasrole('admin') || !$item->payments()->where('status', '=', 'Approved')->exists())
                                            {!! Form::open([
                                                'method'=>'DELETE',
                                                'url' => ['/expenses', $item->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn btn-danger btn-xs',
                                                        'title' => 'Delete expense',
                                                        'onclick'=>'return confirm("Confirm delete?")'
                                                )) !!}
                                            {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $expenses->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
