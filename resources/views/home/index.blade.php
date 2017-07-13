@extends('layouts.master')

@section('title') Login @stop

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

   @if (count( $errors ) > 0)
        @foreach ($errors->all() as $error)
            <div class='bg-danger alert'>{{ $error }}</div>
        @endforeach
    @endif

    <h2><i class='fa fa-lock'></i> Odin Management Console</h2>

{{ Form::open(['role' => 'form','method' => 'POST']) }}

    <div class='form-group'>
        {{ Form::label('username', 'Email') }}
        {{ Form::text('username', null, ['placeholder' => 'Email', 'class' => 'form-control']) }}
    </div>

    <div class='form-group'>
        {{ Form::label('password', 'Password') }}
        {{ Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) }}
    </div>

    <div class='form-group'>
        {{ Form::submit('Login', ['class' => 'btn btn-primary']) }}
    </div>

    {{ Form::close() }}


</div>

@stop