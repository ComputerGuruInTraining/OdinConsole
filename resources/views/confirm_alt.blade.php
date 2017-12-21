{{--Usage: generic confirmation page using layouts.master_layout without app sidebar and header. Useful when not logged into app--}}
@extends('layouts.master')

@section('title')
    Confirmation Page
@stop

@section('content')
    <img src="{{ asset("odin-logo-current.png") }}" alt="Odin Logo" height="60px" width="200px" style="position: absolute; left:30px; top:30px;"/>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default margin-top-alt panel-bg">
                    <div class="panel-heading panel-head">{{$title}}</div>

                    <div class="panel-body">
                        <p> {{ $line1 }}</p>
                        <p> {{ $line2 }}</p>
                    </div>
                    <a href="/" class="btn" style="margin: 20px;
                                        color: white;
                                background-color:#4d2970;
                                font-size: large;">Back to Login</a>

                </div>

            </div>
        </div>
    </div>

@stop