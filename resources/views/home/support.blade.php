@extends('layouts.master')

@section('title') Support @stop

@section('content')

    <img src="{{ asset("/bower_components/AdminLTE/dist/img/ODIN-Logo.png") }}" alt="Odin Logo" height="60px"
         width="200px"
         style="position: absolute; left:30px; top:30px;"/>

    <div class="row">
        <div class="title-bg-app">
            <div class="title-block-app">
                <p class="title-app">Support</pclass>
            </div>
        </div>
        <div class="title-content-app title-content-alt-app">
            <p class="title-heading-app">Questions, Suggestions, Feedbacks, Concerns?</p>
            <p class="title-text-app">We are happy to hear from you</p>
            <p>Email us at: <span class="title-text-app text-bold">odinlitemail@gmail.com</span></p>
        </div>
    </div>
@stop