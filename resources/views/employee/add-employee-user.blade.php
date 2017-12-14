@extends('layouts.master_layout_dob')
@extends('sidebar')

@section('title-item')
    Add Employee
@stop

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

        {{ Form:: open(['role' =>'form', 'url' => '/employee/create-existing/'.$user->id]) }}

        <div class='form-group'>
            {{ Form::label('first_name', 'First Name *') }}
            {{ Form::text('first_name', $user->first_name, ['placeholder' => 'First Name', 'class' => 'form-control', 'disabled' => 'disabled']) }}
        </div>

        <div class='form-group'>
            {{ Form::label('last_name', 'Last Name *') }}
            {{ Form::text('last_name', $user->last_name, ['placeholder' => 'Last Name', 'class' => 'form-control', 'disabled' => 'disabled']) }}
        </div>

        <div class='form-group'>
            {{ Form::label('dob', 'Date of Birth *') }}
            {{ Form::text('dateOfBirth', '', array('class' => 'datepicker',  'onkeypress'=>'return noenter()')) }}

        </div>

        <div class='form-group'>
            *
            {{ Form::label('male', 'Male') }}
            {{ Form::radio('sex', 'M') }}
            {{ Form::label('female', 'Female') }}
            {{ Form::radio('sex', 'F') }}
        </div>
        <div class='form-group'>
            {{ Form::label('mobile','Mobile *')}}
            {{ Form::text('mobile', null, ['placeholder' => 'Mobile', 'class' => 'form-control']) }}

        </div>

        <div class='form-group'>
            {{ Form::label('email', 'Email *') }}
            {{ Form::email('email', $user->email, ['placeholder' => 'Email', 'class' => 'form-control', 'disabled' => 'disabled']) }}

        </div>

        <div class='form-group form-buttons'>
            {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
            <a href="/employees" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        </div>

        {{ Form::close() }}
    </div>
@stop
