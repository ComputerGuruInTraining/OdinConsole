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
        {!! Form::Label('type', 'Role *') !!}
        <select id="role" class="form-control" name="role" onkeypress="return noenter()">
            {{--ROLES that our app supports generating--}}
            @if(count( old('role')) > 0 )
                @if(old('role') == Config::get('constants.ROLE_1'))
                    <option value="{{Config::get('constants.ROLE_1')}}" selected>{{Config::get('constants.ROLE_1')}}</option>
                @else
                    <option value="{{Config::get('constants.ROLE_1')}}">{{Config::get('constants.ROLE_1')}}</option>
                @endif
                @if(old('role') == Config::get('constants.ROLE_2'))
                    <option value="{{Config::get('constants.ROLE_2')}}" selected>{{Config::get('constants.ROLE_2')}}</option>
                @else
                    <option value="{{Config::get('constants.ROLE_2')}}">{{Config::get('constants.ROLE_2')}}</option>
                @endif

            @else
                <option value="{{Config::get('constants.ROLE_1')}}">{{Config::get('constants.ROLE_1')}}</option>
                <option value="{{Config::get('constants.ROLE_2')}}">{{Config::get('constants.ROLE_2')}}</option>
            @endif
        </select>
    </div>

    <div class='form-group'>
        {{ Form::label('first_name', 'First Name *') }}
        {{ Form::text('first_name', null, ['placeholder' => 'First Name', 'class' => 'form-control']) }}
    </div>

    <div class='form-group'>
        {{ Form::label('last_name', 'Last Name *') }}
        {{ Form::text('last_name', null, ['placeholder' => 'Last Name', 'class' => 'form-control']) }}
    </div>

    <div class='form-group'>
        {{ Form::label('email', 'Email *') }}
        {{ Form::email('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) }}
    </div>

    <div class='form-group form-buttons'>
        {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
        <a href="/settings" class="btn btn-info" style="margin-right: 3px;">Cancel</a>

    </div>

    {{ Form::close() }}

</div>

@stop