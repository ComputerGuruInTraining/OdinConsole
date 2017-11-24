{{--Usage: Work in progress. To be used for different route from case-note update--}}

@extends('layouts.master_layout_recent_date')
@extends('sidebar')

@section('title-item')
    Confirmation Page
@stop

@section('page-content')
    <div class="confirm col-md-8 line-space">{{ $theAction }}.</div>
    <div class="col-md-8">
        <a href="/reports-{{$reportId}}-edit" class="btn btn-primary" style="margin-right: 3px;">Back to Manage Report</a>
    </div>

@stop