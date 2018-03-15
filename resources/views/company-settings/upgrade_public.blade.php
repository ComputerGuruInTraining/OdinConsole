{{--Usage: generic confirmation page using layouts.master_layout without app sidebar and header. Useful when not logged into app--}}
@extends('layouts.master')

@section('title')
Upgrade Subscription
@stop

@section('public-custom-scripts')
    @include('company-settings.upgrade_js')

    <style>
        .row{
            height: 100%;
            z-index: inherit;
            margin-top: 50px;
        }

        .box-layout{
            background-image: none !important;
            /*height: 100%;*/
            z-index: inherit;
            /*margin-top: 20px;*/
        }

        .container-fluid{
            height: 86%;
            min-height: 1000px;
            background-image: url(http://www.odincasemanagement.com/wp-content/uploads/2015/11/absurdity.png?id=137) !important;
            margin-top: 110px;
        }

        .padding-left-x-lg{
            padding-left: 18%;
        }

        .content-header{
            margin-top: -55px !important;
        }

        .free-trial-btn{
            color: #4d2970;
            border-radius:7px;
            border: 3px solid #402045;
            padding: 10px 20px;
            margin: 5px !important;
        }

        .free-trial-line1{
            color: #000;
            font-size: 24px;
        }

        .free-trial-div{
            text-align: center;
            font-family: "Trebuchet MS";
            position: inherit;

        }

        .free-trial-line2{
            padding-bottom: 15px;
        }

        .free-trial-line4{
            padding-top: 15px;
        }

        .free-trial-line2, .free-trial-line4{
            color: #5d5d5d;
        }

        .free-trial{
            clear: left;
            padding-top: 100px;
        }

        .separator-line{
            border: 1px solid rgb(235,235,235);
            width: 60%;
            position: relative;
            margin: auto;
            z-index: inherit;
            margin-bottom: 40px;
        }

        @media (max-width: 1110px) {
            .box-tile {
                margin-top: 5%;
            }
        }

    </style>
@stop

@section('content')

    <img src="{{ asset("/bower_components/AdminLTE/dist/img/odinLogoCurr.png") }}" alt="Odin Logo" height="60px"
         width="200px" style="position: absolute; left:30px; top:30px;"/>

    <div><a href="/support" target="_blank"><h4 class="top-text" id="grey-color">Support</h4></a></div>

    {{--purple header border--}}
    <section class="content-header"></section>

    @include('company-settings.upgrade_layout')

@stop