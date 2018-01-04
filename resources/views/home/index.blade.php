@extends('layouts.master')

@section('title') Login @stop

{{--@section('my-styles')--}}

{{--<style>--}}
{{--.top-text {--}}
{{--position: absolute;--}}
{{--right: 50px;--}}
{{--top: 50px;--}}
{{--}--}}

{{--#grey-color {--}}
{{--color: #555;--}}
{{--}--}}

{{--</style>--}}

{{--@stop--}}

@section('content')
    <img src="{{ asset("/bower_components/AdminLTE/dist/img/odinLogoCurr.png") }}" alt="Odin Logo" height="60px"
         width="200px"
         style="position: absolute; left:30px; top:30px;"/>

    <div><a href="/support" target="_blank"><h4 class="top-text" id="grey-color">Support</h4></a></div>
    <div class="container" style="padding-top: 150px;">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" style="font-size: large;">
                @if (count( $errors ) > 0)
                    @foreach ($errors->all() as $error)
                        <div class="alert error">{{ $error }}</div>
                    @endforeach
                @endif
                <div class="panel panel-default" style="border-color: #4d2970;">
                    <div class="panel-heading panel-head">Login</div>
                    <div class="panel-body">
                        {{ Form::open(['role' => 'form','method' => 'POST']) }}
                        <div>
                            <div class='form-group'>
                                <label for="email" class="col-md-4 control-label">E-Mail</label>
                                {{ Form::text('username', null, [
                                'placeholder' => 'Email',
                                 'class' => 'form-control',
                                 'style' =>  'border: 1px solid #4d2970;
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
                                'style' =>  'border: 1px solid #4d2970;
                                    -webkit-text-fill-color: grey;
                                    -webkit-box-shadow: 0 0 0px 1000px white inset;
                                    transition: background-color 5000s ease-in-out 0s;'
                                  ])
                                }}
                            </div>

                            <div class='form-group'>

                                {{ Form::submit('Login', [
                                    'class' => 'btn login-btns',
                                     'style' => 'color: white;
                                     background-color: #4d2970;
                                     font-size: large;'
                                 ])
                                 }}
                            </div>

                            <div>
                                <a href='/reset/link' style="color: #4d2970;" target="_blank">Forgot Password?</a>
                            </div>
                            {{--class="btn" style="margin-right: 3px;--}}
                            {{--color: white;--}}
                            {{--background-color:#4d2970; font-size: large;"--}}
                            <div>
                                <br/>
                                New to ODIN?
                                <a href="/register" style="color:#4d2970; text-decoration: underline;">Register Your Company</a>
                                to get started
                            </div>


                        </div>
                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop