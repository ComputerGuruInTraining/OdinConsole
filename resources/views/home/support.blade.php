@extends('layouts.master')

@section('title') Support @stop

@section('my-styles')

    <style>
        .top-text {
            position: absolute;
            right: 50px;
            top: 50px;
        }

        #grey-color {
            color: #555;
        }

        .row > .title-bg > .title-block {
            background-color: #f5f5f5 !important;
            height: 500px;
            margin-top: 70px;
            width: 100%;
            /*margin-right: -40px;*/
            /*margin-left: -40px;*/
        }

        .title-block {

            padding-top: 200px;
            text-align: center;
        }

        .subtitle {
            font-size: x-large;
            text-align: center;

        }

        .title {
            font-size: 5.4rem;
        }

        .title-heading {
            text-align: center;
            font-size: x-large;
            padding-top: 50px;

        }

        .title-text {
            text-align: center;
            font-size: 22px;

        }

        .title-content {
            height: 400px;
        }
    </style>

@stop

@section('content')

    <img src="{{ asset("/bower_components/AdminLTE/dist/img/ODIN-Logo.png") }}" alt="Odin Logo" height="60px"
         width="200px"
         style="position: absolute; left:30px; top:30px;"/>
    <h5 class="top-text">odinlitemail@gmail.com</h5>


    <div class="row">
        <div class="title-bg">
            <div class="title-block">
                <p class="title">Support</pclass>
            </div>
        </div>
        <div class="title-content">
            <p class="title-heading">Questions, Suggestions, Feedbacks, Concerns?</p>
            <p class="title-text">We are happy to hear from you</p>
        </div>
    </div>
@stop