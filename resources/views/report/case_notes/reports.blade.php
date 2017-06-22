@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Reports
@stop

@section('page-content')
    <div class="col-md-12">

        <div style="padding:15px 0px 10px 0px;">
            <button type="button" class="btn btn-success" onclick="window.location.href='reports/create'">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Generate Report
            </button>
        </div>

        <div class='table-responsive'>
            <h3>Case Notes Report</h3>
            <table class="table table-hover">
                <tr>
                    <th>Date Range (??or report name??)</th>
                    <th>Location</th>
                    <th>Total Hours Monitoring Location</th>
                    <th>Guard Presence at Location (?? or list the names of the guards??)</th>
                    <th>Case Notes</th>
                    <th>Actions</th>
                </tr>
                @foreach($reports as $report)
                    <tr>
                        <td></td>
                        <td>{{$report->location_id}}</td>
                        <td>{{$report->total_hours}}</td>
                        <td>{{$report->total_guards}}</td>
                        <td></td>
                        <td class="column-width">
                            <a href="/reports/{{$report->id}}/edit">Edit</a> | <a href="/confirm-delete-location/{{$report->id}}/" style="color: #cc0000;">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

    </div>

    {{--<div>--}}
        {{--@foreach($reports as $report)--}}
        {{--<li>{{$report->location_id}} {{$report->total_hours}}</li>--}}
        {{--@endforeach--}}
    {{--</div>--}}

@stop