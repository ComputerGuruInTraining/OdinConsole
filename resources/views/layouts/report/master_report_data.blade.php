{{--<!DOCTYPE html>--}}
{{--<html lang='en'>--}}
{{--<head>--}}
    {{--<meta name='viewport' content='width=device-width, initial-scale=1'>--}}
    {{--<title>@yield('title')</title>--}}
    {{--Bootstrap Cdn stylesheet--}}
    {{--<link rel='stylesheet' href='//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css'>--}}
    {{--<!-- Font Awesome -->--}}
    {{--<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">--}}

    {{--Font Family by Google--}}
    {{--<link href='https://fonts.googleapis.com/css?family=Sofia' rel='stylesheet'>--}}

    {{--Bootstrap stylesheet--}}
    {{--<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css") }}"--}}
          {{--type="text/css">--}}
    {{--<!-- Ionicons -->--}}
    {{--<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css">--}}
    {{--<!-- Theme style -->--}}
    {{--<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/dist/css/AdminLTE.css")}}" type="text/css">--}}
    {{--<!-- AdminLTE Skins. We have chosen the skin-blue for this starter--}}
            {{--page. However, you can choose any other skin. Make sure you--}}
            {{--apply the skin class to the body tag so the changes take effect.--}}
    {{---->--}}
    {{--<link rel="{{ asset("/bower_components/adminlte/dist/css/skins/skin-blue.min.css")}}" type="text/css">--}}

    {{--necessary at least for date picker, if not other features--}}
    {{--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">--}}
    {{--<link rel="stylesheet" href="/resources/demos/style.css">--}}


    {{--<link rel="stylesheet" href="{{asset('css/main.css')}}">--}}
    {{--<script type="text/javascript"--}}
    {{--src="{{asset('/bower_components/adminlte/plugins/jQuery/jquery-3.2.1.js')}}"></script>--}}


{{--<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->--}}
    {{--<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->--}}
    {{--<!--[if lt IE 9]>--}}
    {{--<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>--}}
    {{--<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>--}}
    {{--<![endif]-->--}}

    {{--<script type="text/javascript">--}}
        {{--function noenter() {--}}
            {{--return !(window.event && window.event.keyCode == 13);--}}
        {{--}--}}
    {{--</script>--}}

    {{--Company Settings Page & logged in support page ie --}}
    {{--@yield('custom-scripts')--}}

    {{--@yield('custom-styles')--}}

{{--</head>--}}
{{--<body class="hold-transition skin-blue sidebar-mini">--}}
{{--<div class='container-fluid'>--}}
    {{--<div class="wrapper">--}}
    {{--FIXME: scroll-bar change height as a bit buggy--}}
    {{--<!-- Main Header--}}
            {{--<header class="main-header">  -->--}}
    {{--@include('header')--}}

    {{--<!-- Sidebar -->--}}
    {{--@include('sidebar')--}}


@extends('layouts.master_layout')
@extends('sidebar')

@section('page-content')
    <div class="col-md-12">
        <div class='table-responsive'>

            <div style="padding:15px 0px 10px 0px;">
                <a href="{{ route('pdf',['id' => $report->id, 'download'=>'pdf']) }}" class="btn btn-primary">Download
                    PDF</a>
            </div>

            <h3 class="report-title" id="report-heading">{{$report->type}} Report</h3>
            <table class="col-md-12 margin-bottom">
                <tr><h4 id="report-date">{{$start}} - {{$end}}</h4></tr>
                <tr class="report-header-row grey-larger">


                    <td>@yield('entity-show')</td>
                    </td>
                    <td class="report-header grey-larger">@yield('entity-value-show')</td>
                </tr>
                <tr class="report-header-row grey-larger">
                    <td>@yield('total1-show')</td>
                    <td class="report-header grey-larger">@yield('total1-val-show')</td>
                </tr>
                <tr class="report-header-row">
                    <td>@yield('total2-show')</td>
                    <td class="report-header">@yield('total2-val-show')</td>
                </tr>
            </table>

            <table class="table table-hover bottom-border">
                <tr>
                    <th>@yield('colHeading1-show')</th>
                    <th>@yield('colHeading2-show')</th>
                    <th>@yield('colHeading3-show')</th>
                    <th>@yield('colHeading4-show')</th>
                    <th>@yield('colHeading5-show')</th>
                    <th>@yield('colHeading6-show')</th>
                    <th>@yield('colHeading7-show')</th>
                    <th>@yield('colHeading8-show')</th>
                </tr>

                @yield('report-content-show')

            </table>
        </div>
    </div>
    <br/>
    <br/>

    @yield('add1-report-content-show')

@stop

