@extends('layouts.report.pdf_layout')

@section('report-address-bar')
    Management Report
@stop

@section('entity')
    Premise:
@stop

@section('entity-value')
    {{$location->address}}
@stop

@section('total1-desc')
    Total Hours Monitoring Premise:
@stop

@section('total1-val')
    WIP
    {{--{{$report->totalHours}}--}}
@stop

@section('colHeading1')
    Date
@stop

@section('colHeading2')
    Check In
@stop

@section('colHeading3')
    Check Out
@stop

@section('colHeading4')
    Action
@stop

@section('colHeading5')
    Case ID
@stop

@section('colHeading6')
    Guard
@stop

@section('colHeading7')
    Total Time (min/s)
@stop

@section('colHeading8')
    Geo Location
@stop

@section('report-content')
    @include('report.management.shared')
@stop

@section('additional-report-content')
    @include('layouts.report.case_details')
@stop


