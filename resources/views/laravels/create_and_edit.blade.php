@extends('layouts.app')

@section('content')

<div class="container">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            
            <div class="panel-heading">
                <h1>
                    <i class="glyphicon glyphicon-edit"></i> Laravel /
                    @if($laravel->id)
                        Edit #{{$laravel->id}}
                    @else
                        Create
                    @endif
                </h1>
            </div>

            @include('common.error')

            <div class="panel-body">
                @if($laravel->id)
                    <form action="{{ route('laravels.update', $laravel->id) }}" method="POST" accept-charset="UTF-8">
                        <input type="hidden" name="_method" value="PUT">
                @else
                    <form action="{{ route('laravels.store') }}" method="POST" accept-charset="UTF-8">
                @endif

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    
                <div class="form-group">
                	<label for="name-field">Name</label>
                	<input class="form-control" type="text" name="name" id="name-field" value="{{ old('name', $laravel->name ) }}" />
                </div> 
                <div class="form-group">
                	<label for="description-field">Description</label>
                	<textarea name="description" id="description-field" class="form-control" rows="3">{{ old('description', $laravel->description ) }}</textarea>
                </div> 
                <div class="form-group">
                    <label for="subscriber_count-field">Subscriber_count</label>
                    <input class="form-control" type="text" name="subscriber_count" id="subscriber_count-field" value="{{ old('subscriber_count', $laravel->subscriber_count ) }}" />
                </div>

                    <div class="well well-sm">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a class="btn btn-link pull-right" href="{{ route('laravels.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection