@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item') Create User @stop

@section('page-content')

    <div class='form-pages col-md-8'>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    {{ Form::open(['role' => 'form', 'url' => '/user', 'action' => 'POST']) }}

    <div class='form-group'>
        {{ Form::label('first_name', 'First Name') }}
        {{ Form::text('first_name', null, ['placeholder' => 'First Name', 'class' => 'form-control']) }}
    </div>

    <div class='form-group'>
        {{ Form::label('last_name', 'Last Name') }}
        {{ Form::text('last_name', null, ['placeholder' => 'Last Name', 'class' => 'form-control']) }}
    </div>

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

    <div class='form-group form-buttons'>
        {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
        <a href="/settings" class="btn btn-info" style="margin-right: 3px;">Cancel</a>

    </div>

    {{ Form::close() }}

</div>

@stop