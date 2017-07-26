@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item') Create User @stop

@section('page-content')

    <div class='form-pages col-md-8'>

    @if (count( $errors ) > 0) 
        @foreach ($errors->all() as $error)
            <div class='bg-danger alert'>{{ $error }}</div>
        @endforeach
    @endif

    {{--<h1><i class='fa fa-user'></i> Add User</h1>--}}

    {{ Form::open(['role' => 'form', 'url' => '/user', 'action' => 'POST']) }}

    <div class='form-group'>
        {{ Form::label('first_name', 'First Name') }}
        {{ Form::text('first_name', null, ['placeholder' => 'First Name', 'class' => 'form-control']) }}
    </div>

    <div class='form-group'>
        {{ Form::label('last_name', 'Last Name') }}
        {{ Form::text('last_name', null, ['placeholder' => 'Last Name', 'class' => 'form-control']) }}
    </div>

    {{--<div class='form-group'>--}}
        {{--{{ Form::label('username', 'Username') }}--}}
        {{--{{ Form::text('username', null, ['placeholder' => 'Username', 'class' => 'form-control']) }}--}}
    {{--</div>--}}

    <div class='form-group'>
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) }}
    </div>

    {{--<div class='form-group'>--}}
        {{--{{ Form::label('password', 'Password') }}--}}
        {{--{{ Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) }}--}}
    {{--</div>--}}

    {{--<div class='form-group'>--}}
        {{--{{ Form::label('password_confirmation', 'Confirm Password') }}--}}
        {{--{{ Form::password('password_confirmation', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) }}--}}
    {{--</div>--}}

    <div class='form-group'>
        {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
    </div>

    {{ Form::close() }}

</div>

@stop