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
{{--        {{ Form::open(['route' => ['employees.existing', $user->id], 'method'=>'put']) }}--}}


        <div class='form-group'>
            {!! Form::Label('users', 'Select User *') !!}
            <select class="form-control" name="user" onkeypress="return noenter()">
                {{--if there is old input--}}
                {{--@if(count(old('user')) > 0)--}}
                    {{--@foreach($users as $user)--}}
                        {{--@if(old('user') == $user->id)--}}
                            {{--mark the old input as selected--}}
                            {{--<option value="{{$user->id}}" selected>{{$user->first_name}}</option>--}}
                        {{--@else--}}
                            {{--other values in list--}}
                            {{--<option value="{{$user->id}}">{{$user->first_name}}</option>--}}
                        {{--@endif--}}
                    {{--@endforeach--}}

                {{--@else--}}

                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->first_name}}</option>
                    @endforeach

                {{--@endif--}}
            </select>
        </div>

        <div class='form-group form-buttons'>
            {{ Form::submit('Next', ['class' => 'btn btn-primary']) }}
            <a href="/reports" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        </div>

        {{--<div class='form-group padding-top'>--}}
            {{--{{ Form::label('existing','Would you like to add an existing user as an employee?') }}--}}

            {{--{{ Form::radio('existing', 'true') }}--}}
            {{--{{ Form::radio('existing', 'false') }}--}}

        {{--</div>--}}

        {{ Form::close() }}

    </div>
@stop