@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Create New user</div>
                    <div class="panel-body">
                        <a href="{{ url('/users') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::open(['url' => '/users', 'class' => 'form-horizontal']) !!}

                        <div class="app_form_container">
                            <div class="form-group">
                                {{ Form::label('name', 'Name') }}
                                {{ Form::text('name', '', array('class' => 'form-control', 'required')) }}
                            </div>
                        
                            <div class="form-group">
                                {{ Form::label('email', 'Email') }}
                                {{ Form::email('email', '', array('class' => 'form-control', 'required')) }}
                            </div>
                        
                            <div class='form-group'>
                                @foreach ($roles as $role)
                                    {{ Form::checkbox('roles[]',  $role->id ) }}
                                    {{ Form::label($role->name, ucfirst($role->name)) }}<br>
                                @endforeach
                            </div>
                        
                            <div class="form-group">
                                {{ Form::label('password', 'Password') }}<br>
                                {{ Form::password('password', array('class' => 'form-control', 'required')) }}
                            </div>
                        
                            <div class="form-group">
                                {{ Form::label('password', 'Confirm Password') }}<br>
                                {{ Form::password('password_confirmation', array('class' => 'form-control', 'required')) }}
                            </div>
                        
                            {{ Form::submit('Create', array('class' => 'btn btn-primary')) }}
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
