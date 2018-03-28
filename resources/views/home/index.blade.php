@extends('layouts.master')

@section('title') Login @stop

@section('content')
    <img src="{{ asset("/bower_components/AdminLTE/dist/img/odinLogoCurr.png") }}" alt="Odin Logo" height="60px"
         width="200px"
         style="position: absolute; left:30px; top:30px;"/>

    <div class="standard-links grey-color"><a href="/upgrade" target="_blank" ><h4 class="top-text-left">Pricing</h4></a></div>
    <div class="standard-links grey-color"><a href="/support" target="_blank"><h4 class="top-text">Support</h4></a></div>
    <div class="container" style="padding-top: 150px;">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" style="font-size: large;">
                @if (count( $errors ) > 0)
                    @foreach ($errors->all() as $error)
                        <div class="alert error">{{ $error }}</div>
                    @endforeach
                @endif
                @if (isset($term))
                        <div class="alert green-font padding-top-btm-none">UPGRADE SUBSCRIPTION</div>
                        <div class="alert grey-font padding-top-btm-none">First things first, please login or register your company account
                            and then we'll finalise the {{ $term }} subscription for {{$numUsers}} users.</div>
                @endif
                <div class="panel panel-default" style="border-color: #4d2970;">

                    <div class="panel-heading panel-head">Login</div>
                    <div class="panel-body">

                        @if(!isset($term))
                            {{ Form::open(['role' => 'form','method' => 'POST']) }}
                        @else
                            {{ Form::open(['role' => 'form', 'method' => 'POST', 'url' => '/login/upgrade/'.$plan.'/'.$term]) }}
                        @endif

                        {{ csrf_field() }}
                        <div>
                            <div class='form-group'>
                                <label for="email" class="col-md-4 control-label">E-Mail</label>
                                {{ Form::text('username', null, [
                                'placeholder' => 'Email',
                                 'class' => 'form-control',
                                 'style' =>  '-webkit-text-fill-color: grey;
                                    -webkit-box-shadow: 0 0 0px 1000px white inset;
                                    transition: background-color 5000s ease-in-out 0s;'
                                ]) }}
                            </div>

                            <div class='form-group'>
                                <label for="email" class="col-md-4 control-label">Password</label>
                                {{ Form::password('password', [
                                'placeholder' => 'Password',
                                 'class' => 'form-control login-input',
                                'style' =>  '-webkit-text-fill-color: grey;
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
                            @if(!isset($term))
                                <div>
                                    <br/>
                                    New to ODIN Case Management?
                                    <a href="/register" style="color:#4d2970; text-decoration: underline;">Register Your Company</a>
                                    to get started
                                </div>
                            @else
                                <div>
                                    <br/>
                                    New to ODIN Case Management?
                                    <a href="/register/plan/{{$plan}}/{{$term}}" style="color:#4d2970; text-decoration: underline;">Register Your Company</a>
                                    to get started
                                </div>
                            @endif

                        </div>
                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop