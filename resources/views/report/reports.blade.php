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
            <table class="table table-hover">
                <tr>
                    <th>Report Type</th>
                    <th>Date Range</th>

                    {{--<th>Location</th>--}}
                    {{--<th>Total Hours Monitoring Location</th>--}}
                    {{--<th>Guard Presence at Location (?? or list the names of the guards??)</th>--}}
                    <th>Actions</th>
                </tr>
                @foreach($reports as $report)
                    <tr>
                        <td>{{$report->type}}</td><!--TODO: make case notes a variable in code-->
                        <td>{{$report->date_start}} - {{$report->date_end}}</td>
                        {{--<td></td>--}}
                        {{--<td>{{$report->total_hours}}</td>--}}
                        {{--<td>{{$report->total_guards}}</td>--}}
                        {{--<td></td>--}}

                        <td>
                            <a href="/reports/{{$report->id}}">View</a> | <a href="#" style="color: #cc0000;">Delete</a>
                        </td>
                    </tr>
                @endforeach

                {{--@foreach($cases as $case)--}}
                    {{--<td>{{$case->id}}</td>--}}
                {{--@endforeach--}}
            </table>
        </div>

    </div>

    {{--<div>--}}
        {{--@foreach($reports as $report)--}}
        {{--<li>{{$report->location_id}} {{$report->total_hours}}</li>--}}
        {{--@endforeach--}}
    {{--</div>--}}

@stop