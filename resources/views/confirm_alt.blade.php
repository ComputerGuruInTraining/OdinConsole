{{--Usage: generic confirmation page using layouts.master_layout without app sidebar and header. Useful when not logged into app--}}

@extends('layouts.master')

@section('title')
    Confirmation Page
@stop

@section('content')
    <img src="{{ asset("/bower_components/AdminLTE/dist/img/ODIN-Logo.png") }}" alt="Odin Logo" height="60px" width="200px" style="position: absolute; left:30px; top:30px;"/>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default margin-top-alt panel-bg">
                    <div class="panel-heading panel-head">{{$title}}</div>

                    <div class="panel-body">
                        <p> {{ $line1 }}</p>
                        <p> {{ $line2 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop