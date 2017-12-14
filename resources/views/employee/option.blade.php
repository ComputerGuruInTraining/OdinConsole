{{--/**--}}
 {{--* Created by PhpStorm.--}}
 {{--* User: bernie--}}
 {{--* Date: 4/12/17--}}
 {{--* Time: 2:14 PM--}}
 {{--*/--}}

@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Add Employee
@stop

@section('page-content')
    <div class='form-pages col-md-8'>

        {{ Form::open(['role' => 'form', 'url' => '/employees/create-existing-user']) }}

        <div class='form-group'>
            {!! Form::Label('users', 'Select User *') !!}
            <select class="form-control" name="user" onkeypress="return noenter()">

                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                    @endforeach

            </select>
        </div>

        <div class='form-group form-buttons'>
            {{ Form::submit('Next', ['class' => 'btn btn-primary']) }}
            <a href="/reports" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        </div>

        {{ Form::close() }}

    </div>
@stop