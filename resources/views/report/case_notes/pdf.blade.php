@extends('layouts.pdf_layout')

@section('report-address-bar')
    Case Notes Report
@stop

@section('entity')
    Premise:
@stop

@section('entity-value')
    {{$cases->location->address}}
@stop

@section('total1-desc')
    Guard Presence at Location:
@stop

@section('total1-val')
    {{$cases->reportCases->total_guards}}
@stop

@section('colHeading1')
    Date
@stop

@section('colHeading2')
    Case Title
@stop

@section('colHeading3')
    Case Description
@stop

@section('colHeading4')
    Case Image
@stop

@section('colHeading5')
    Reporting Guard
@stop

@section('colHeading6')
    Case Id
@stop

@section('report-content')

    {{--Check to ensure there are case notes or else an error will be thrown--}}

    @if(count($cases->reportCaseNotes) != 0)
        @foreach($groupCases as $index => $note)
            <tbody class="group-list">

            <tr>
                <td class="report-title">{{$index}}</td>
                {{--<td></td>--}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @foreach ($groupCases->get($index) as $item)
                <tr>
                    <td></td>
                    {{--                                    <td>{{$item->case_time}}</td>--}}
                    <td>{{$item->title}}</td>
                    <td>{{$item->description}}</td>
                    <td>{{$item->hasImg}}</td>
                    <td>{{$item->employee}}</td>
                    <td>{{$item->case_id}}</td>
                </tr>
            @endforeach
            </tbody>
        @endforeach

    @else
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endif
@stop

{{--@section('')--}}

{{--@stop--}}

{{--@section('')--}}

{{--@stop--}}

{{--@section('')--}}

{{--@stop--}}

{{--@section('')--}}

{{--@stop--}}

{{--@section('')--}}

{{--@stop--}}

{{--@section('')--}}

{{--@stop--}}

{{--@section('')--}}

{{--@stop--}}


{{--<html>--}}
{{--<head>--}}
    {{--<title>Case Notes Report PDF</title>--}}

{{--internal stylesheet being used as external stylesheet takes much longer to load, even if only contains the same styles--}}
    {{--<style>--}}
        {{--.report-title {--}}
            {{--/*color: #4d2970;*/--}}
            {{--margin-left: 5px;--}}
        {{--}--}}

        {{--#report-heading{--}}
            {{--font-family: Futura, "Trebuchet MS", Arial, sans-serif;--}}
            {{--font-size: x-large;--}}
        {{--}--}}

        {{--.report-header{--}}
            {{--padding-left: 15px;--}}
            {{--font-size: large;--}}
        {{--}--}}

        {{--.grey-larger{--}}
            {{--color: #777;--}}
            {{--font-size: 18px;--}}
        {{--}--}}

        {{--#report-date{--}}
            {{--color: #777;--}}
        {{--}--}}

        {{--.margin-table {--}}
            {{--margin-bottom: 25px !important;--}}
        {{--}--}}

        {{--/*Fonts Large*/--}}
        {{--.table > tbody > tr > td,--}}
        {{--.table >tbody >tr > th,--}}
        {{--body--}}
        {{--{--}}
            {{--font-size: large !important;--}}
            {{--text-align: left;--}}
        {{--}--}}

        {{--td, tr{--}}
            {{--height: 18px;--}}
        {{--}--}}

        {{--.table > tbody > tr > th,--}}
        {{--.table > tbody > tr > td{--}}
            {{--padding: 8px;--}}

        {{--}--}}

        {{--.table > tbody > tr > th{--}}
            {{--border-top: 1px solid #f4f4f4;--}}
            {{--border-bottom: 1px solid #f4f4f4;--}}

        {{--}--}}
        {{--.table td {--}}
            {{--border-bottom: 1px solid #f4f4f4;--}}
        {{--}--}}

        {{--.table-responsive > .table tr th:last-child,--}}
        {{--.table-responsive > .table tr td:last-child {--}}
            {{--border-right: 1px solid #f4f4f4;--}}
        {{--}--}}

        {{--.table-responsive > .table tr th:first-child,--}}
        {{--.table-responsive > .table tr td:first-child {--}}
            {{--border-left: 1px solid #f4f4f4;--}}
        {{--}--}}

        {{--/*   Following styles sourced from--}}
            {{--<link rel="stylesheet" href="{{ asset("/bower_components/adminlte/bootstrap/css/bootstrap.min.css") }}" type="text/css">--}}
        {{--*/--}}

        {{--.col-md-12  {--}}
            {{--position: relative;--}}
            {{--min-height: 1px;--}}
            {{--padding-right: 15px;--}}
            {{--padding-left: 15px;--}}
        {{--}--}}

        {{--.table-responsive {--}}
            {{--overflow: auto;--}}
        {{--}--}}

        {{--.table-responsive > .table tr th,--}}
        {{--.table-responsive > .table tr td {--}}
            {{--white-space: normal !important;--}}
        {{--}--}}

        {{--.table-responsive {--}}
            {{--min-height: .01%;--}}
            {{--overflow-x: auto;--}}
        {{--}--}}

        {{--.table-report, table>tr>td.table-report{--}}
            {{--padding: -15px -20px -15px -10px;--}}
        {{--}--}}

        {{--@media screen and (max-width: 767px) {--}}
            {{--.table-responsive {--}}
                {{--width: 100%;--}}
                {{--margin-bottom: 15px;--}}
                {{--overflow-y: hidden;--}}
                {{---ms-overflow-style: -ms-autohiding-scrollbar;--}}
            {{--}--}}

            {{--.table-responsive > .table {--}}
                {{--margin-bottom: 0;--}}
            {{--}--}}

            {{--.table-responsive > .table > thead > tr > th,--}}
            {{--.table-responsive > .table > tbody > tr > th,--}}
            {{--.table-responsive > .table > tfoot > tr > th,--}}
            {{--.table-responsive > .table > thead > tr > td,--}}
            {{--.table-responsive > .table > tbody > tr > td,--}}
            {{--.table-responsive > .table > tfoot > tr > td {--}}
                {{--white-space: nowrap;--}}
            {{--}--}}
        {{--}--}}
        {{--@media (min-width: 992px) {--}}
            {{--.col-md-12 {--}}
                {{--float: left;--}}
            {{--}--}}

            {{--.col-md-12 {--}}
                {{--width: 100%;--}}
            {{--}--}}
        {{--}--}}

        {{--/*override user agent style*/--}}
        {{--table {--}}
            {{--border-collapse: separate;--}}
            {{--border-spacing: 0px;--}}
            {{---webkit-border-horizontal-spacing: 0px;--}}
            {{---webkit-border-vertical-spacing: 0px;--}}
        {{--}--}}

    {{--</style>--}}

{{--</head>--}}
{{--<body>--}}
    {{--<div class="col-md-12">--}}
        {{--<div>--}}
            {{--<div class="table-responsive">--}}
                {{--<div>--}}
                    {{--<h3 class="report-title" id="report-heading">{{$report->type}} Report</h3>--}}
                {{--</div>--}}
                {{--<div class="col-md-12 margin-table">--}}
                    {{--<table>--}}
                    {{--<tr><td class="table-report grey-larger"><h4 id="report-date grey-larger" >{{$start}} - {{$end}}</h4></td></tr>--}}
                        {{--<tr class="table-report"><td class="report-header-row table-report grey-larger"><p>Premise:</p></td>--}}
                            {{--<td class="table-report"><p class="report-header table-report grey-larger">{{$cases->location->address}}</p></td>--}}
                        {{--</tr>--}}
                        {{--<tr class="table-report"><td  class="report-header-row table-report"><p>Hours Monitoring Premise:</p></td>--}}
                        {{--<td class="table-report"><p class="report-header table-report"> {{$cases->reportCases->total_hours}}</p></td></tr>--}}
                        {{--<tr class="table-report"><td  class="report-header-row table-report grey-larger"><p>Guard Presence at Location:</p></td>--}}
                            {{--<td class="table-report grey-larger"><p class="report-header table-report">{{$cases->reportCases->total_guards}}</p></td>--}}
                        {{--</tr>--}}
                    {{--</table>--}}
                {{--</div>--}}

                {{--<table class="table">--}}
                    {{--if there are case notes to report--}}
                    {{--<tr>--}}
                        {{--<th>Premise</th>--}}
                        {{--<th>Date</th>--}}
                        {{--<th>Time</th>--}}
                        {{--<th>Case Title</th>--}}
                        {{--<th>Case Description</th>--}}
                        {{--<th>Case Image</th>--}}
                        {{--<th>Reporting Guard</th>--}}
                        {{--<th>Case Id</th>--}}
                    {{--</tr>--}}
                    {{--Check to ensure there are case notes or else an error will be thrown--}}
                    {{--@if(count($cases->reportCaseNotes) != 0)--}}

                        {{--@foreach($groupCases as $index => $note)--}}
                            {{--<tbody class="group-list">--}}

                            {{--<tr>--}}
                                {{--<td class="report-title">{{$index}}</td>--}}
                                {{--<td></td>--}}
                                {{--<td></td>--}}
                                {{--<td></td>--}}
                                {{--<td></td>--}}
                                {{--<td></td>--}}
                                {{--<td></td>--}}
                            {{--</tr>--}}
                            {{--@foreach ($groupCases->get($index) as $item)--}}
                                {{--<tr>--}}
                                    {{--<td></td>--}}
{{--                                    <td>{{$item->case_time}}</td>--}}
                                    {{--<td>{{$item->title}}</td>--}}
                                    {{--<td>{{$item->description}}</td>--}}
                                    {{--<td>{{$item->hasImg}}</td>--}}
                                    {{--<td>{{$item->employee}}</td>--}}
                                    {{--<td>{{$item->case_id}}</td>--}}
                                {{--</tr>--}}
                            {{--@endforeach--}}
                            {{--</tbody>--}}
                        {{--@endforeach--}}

                    {{--@else--}}
                        {{--<tr>--}}
                            {{--<td></td>--}}
                            {{--<td></td>--}}
                            {{--<td></td>--}}
                            {{--<td></td>--}}
                            {{--<td></td>--}}
                            {{--<td></td>--}}
                        {{--</tr>--}}
                    {{--@endif--}}
                {{--</table>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</body>--}}
{{--</html>--}}
