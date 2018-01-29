@extends('layouts.report.pdf_layout')

@section('report-address-bar')
    Individual Report
@stop

@section('entity-value')
    {{$reportInd[0]->first_name}} {{$reportInd[0]->last_name}}
@stop

@section('total1-desc')
    Total Hours Worked:
@stop

@section('total1-val')
    {{$reportInd[0]->total_hours_worked}} hours
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
    Location
@stop

@section('colHeading5')
    Action
@stop

@section('colHeading6')
    Case ID
@stop

@section('colHeading7')
    Total Time (min/s)
@stop

@section('colHeading8')
    Geo Location
@stop

@section('report-content')
    @include('report.emp.shared')
@stop


