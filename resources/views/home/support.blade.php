@extends('layouts.master')

@section('title') Support @stop

{{--@section('my-styles')--}}

    {{--<style>--}}
        {{--.top-text {--}}
            {{--position: absolute;--}}
            {{--right: 50px;--}}
            {{--top: 50px;--}}
        {{--}--}}

        {{--.row>.title-bg>.title-block{--}}
            {{--background-color: #f5f5f5 !important;--}}
            {{--height: 150px;--}}
            {{--margin-top: 50px;--}}
            {{--width: 100%;--}}
        {{--}--}}

        {{--.title-block {--}}
            {{--padding-top: 50px;--}}
            {{--text-align: center;--}}
        {{--}--}}

        {{--.title {--}}
            {{--font-size: 3.8rem;--}}
        {{--}--}}

        {{--.title-heading {--}}
            {{--text-align: center;--}}
            {{--font-size: x-large;--}}
            {{--padding-top: 50px;--}}
        {{--}--}}

        {{--.title-text {--}}
            {{--text-align: center;--}}
            {{--font-size: 22px;--}}
        {{--}--}}

        {{--.title-content {--}}
            {{--height: 400px;--}}
        {{--}--}}
    {{--</style>--}}

{{--@stop--}}

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
            <p class="title-text-app">Email us at: odinlitemail@gmail.com</p>
        </div>
    </div>
@stop