@extends('layouts.master_layout_alt_header')

@section('title-item') Support @stop

@section('page-content')
    <div class='form-pages content-app master_alt support-pg'>

        {{--<div class="content-app title-content-alt-app">--}}
            <p class="heading-app">Questions, Suggestions, Feedbacks, Concerns?</p>
            {{--<p class="text-app">We are happy to hear from you</p>--}}
            <p>Email us at: <span class="text-bold">{{Config::get('constants.COMPANY_EMAIL')}}</span></p>
        {{--</div>--}}
    </div>
@stop