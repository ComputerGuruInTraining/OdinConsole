@extends('layouts.master')

@section('title') Login @stop

@section('content')

    <div>
        <img src="{{ asset("/bower_components/AdminLTE/dist/img/ODIN-Logo.png") }}" alt="Odin Logo" height="60px" width="200px" style="position: absolute; left:30px; top:30px;"/>
        <div class='col-lg-4 col-lg-offset-4' style="padding-top: 150px;">
            @if (count( $errors ) > 0)
                @foreach ($errors->all() as $error)
                    <div class='bg-danger alert'>{{ $error }}</div>
                @endforeach
            @endif

            {{--<h2><i class='fa fa-lock'></i> Odin Management Console</h2>--}}

            {{ Form::open(['role' => 'form','method' => 'POST']) }}
            <div style="height: 300px; background-color: #eae6ee; padding-top: 150px; padding: 100px 50px 70px 50px; border-radius: 15px;">
                <div class='form-group'>
                    {{--{{ Form::label('username', 'Email') }}--}}
                    {{ Form::text('username', null, [
                    'placeholder' => 'Email',
                     'class' => 'form-control',
                     'style' =>  'border: 1px solid #663974;
                        -webkit-text-fill-color: grey;
                        -webkit-box-shadow: 0 0 0px 1000px white inset;
                        transition: background-color 5000s ease-in-out 0s;'
                    ]) }}
                </div>

                <div class='form-group'>
                    {{--{{ Form::label('password', 'Password') }}--}}
                    {{ Form::password('password', [
                    'placeholder' => 'Password',
                     'class' => 'form-control login-input',
                    'style' =>  'border: 1px solid #663974;
                        -webkit-text-fill-color: grey;
                        -webkit-box-shadow: 0 0 0px 1000px white inset;
                        transition: background-color 5000s ease-in-out 0s;'
                      ])
                    }}
                </div>

                <div class='form-group'>
                    {{ Form::button('Create Account', ['class' => 'btn login-btns', 'style' => 'color: white; background-color: #663974; margin-right: 160px;']) }}
                    {{ Form::submit('Login', ['class' => 'btn login-btns', 'style' => 'color: white; background-color: #663974; text-align: right;']) }}
                </div>
            </div>
            {{ Form::close() }}

        </div>
    </div>

@stop