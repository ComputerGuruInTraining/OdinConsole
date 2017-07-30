@extends('layouts.master')

@section('title') Login @stop

@section('content')

    <img src="{{ asset("/bower_components/AdminLTE/dist/img/ODIN-Logo.png") }}" alt="Odin Logo" height="60px" width="200px"
         style="position: absolute; left:30px; top:30px;"/>
    <div class="container" style="padding-top: 150px;">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" style="font-size: large;">
                @if (count( $errors ) > 0)
                    @foreach ($errors->all() as $error)
                        <div class="alert" style="text-align: center; color: #B22222; font-size: large;">{{ $error }}</div>
                    @endforeach
                @endif
                <div class="panel panel-default" style="border-color: #663974;">
                    <div class="panel-heading"  style="color: white; background-color: #663974;">Login</div>
                    <div class="panel-body">
                        {{ Form::open(['role' => 'form','method' => 'POST']) }}
                        <div>
                            <div class='form-group'>
                                <label for="email" class="col-md-4 control-label">E-Mail</label>
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
                                <label for="email" class="col-md-4 control-label">Password</label>
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
                                {{ Form::button('Create Account', [
                                'class' => 'btn login-btns',
                                'style' => 'color: white;
                                background-color: #663974;
                                font-size: large;'
                                ]) }}
                                {{ Form::submit('Login', [
                                    'class' => 'btn login-btns',
                                     'style' => 'color: white;
                                     background-color: #663974;
                                     position: absolute;
                                     right: 30px;
                                     font-size: large;'
                                 ])
                                 }}
                            </div>
                            <div style="text-align: center;">
                                <a href='/reset/link' style="color: #663974; text-align: center;">Forgot Password?</a>
                            </div>
                        </div>
                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop