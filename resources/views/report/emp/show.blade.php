{{--@extends('layouts.master_layout')--}}
@extends('layouts.report.master_report_data')

{{--@section('entity-show')--}}
    {{--Premise:--}}
{{--@stop--}}

@section('entity-value-show')
@stop

@section('total1-show')
    Total Hours Worked:
@stop

@section('total1-val-show')
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
    Location
@stop

@section('colHeading5-show')
    Action
@stop

@section('colHeading6-show')
    Case ID
@stop

@section('colHeading7-show')
    Total Time
@stop

@section('colHeading8-show')
    Geo Location
@stop

@section('report-content-show')
    @include('report.emp.shared')
@stop


