@extends('layouts.report.master_report_data')

{{--@section('entity-show')--}}
    {{--Premise:--}}
{{--@stop--}}

@section('entity-value-show')
    {{$location->address}}
@stop

@section('total1-show')
    Number of Checks at Premise:
@stop

@section('total1-val-show')
    {{$total}}
@stop

@section('total2-show')
    Total Hours Monitoring Premise:
@stop

@section('total2-val-show')
    {{$report->totalHours}}
@stop

@section('colHeading1-show')
    Date
@stop

@section('colHeading2-show')
    Check In
@stop

@section('colHeading3-show')
    Check Out
@stop

@section('colHeading4-show')
    Action
@stop

@section('colHeading5-show')
    Case ID
@stop

@section('colHeading6-show')
    Case Note Title
@stop

@section('colHeading7-show')
    Description
@stop

@section('colHeading8-show')
    Images
@stop

@section('colHeading9-show')
    Manage
@stop

@section('report-content-show')
    @include('report.client.shared')
@stop

@section('add1-report-content-show')
    @include('layouts.report.case_details')
@stop

