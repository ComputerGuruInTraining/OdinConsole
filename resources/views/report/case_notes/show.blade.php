@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')

@stop

@section('page-content')
    <div class="col-md-12">
        <div>
            <div class='table-responsive'>
                <h3 class="report-title">{{$report->type}} Report</h3>
                <div class="report-header">
                    <h4>{{$start}} - {{$end}}</h4>
                    <h4>Premise: {{$cases->location}}</h4>
                    <h4>Hours Monitoring Premise:  {{$cases->reportCases->total_hours}}</h4>
                    <h4>Guard Presence at Location: {{$cases->reportCases->total_guards}}</h4>
                </div>
                <table class="table table-hover">
                    {{--if there are case notes to report--}}
                    <tr>
                        {{--<th>Premise</th>--}}
                        <th>Date</th>
                        <th>Time</th>
                        <th>Case Title</th>
                        <th>Case Description</th>
                        <th>Reporting Guard</th>
                    </tr>
                    {{--for the first record, display more data--}}
                    {{--<tr>--}}
                    {{--<td>{{$cases->reportCaseNotes[0]->case_date}}</td>--}}
                    {{--<td>{{$cases->reportCaseNotes[0]->description}}</td>--}}
                    {{--<td>{{$cases->reportCaseNotes[0]->title}}</td>--}}
                    {{--</tr>--}}
                    {{--for all the other case notes:--}}
                    @if(count($cases->reportCaseNotes) != 0)

                        {{--@for($i=0; $i < count($cases->reportCaseNotes); $i++)--}}
                        @foreach($groupCases as $index => $note)
                            <tbody class="group-list">

                            <tr>
                            <td>{{$index}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @foreach ($groupCases->get($index) as $item)
                                <tr>
                                    <td></td>
                                    <td>{{$item->case_time}}</td>
                                    <td>{{$item->description}}</td>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->employee}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        @endforeach

                    @else
                        {{--<tr>--}}
                        {{--<th>Location</th>--}}
                        {{--<th>Total Hours Monitoring Location</th>--}}
                        {{--<th>Guard Presence at Location (?? or list the names of the guards??)</th>--}}
                        {{--<th>Actions</th>--}}
                        {{--</tr>--}}
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        {{--<td>{{$cases->location}}</td>--}}
                        {{--<td>{{$cases->reportCases->total_hours}}</td>--}}
                        {{--<td>{{$cases->reportCases->total_guards}}</td>--}}

                        {{--<td>{{$caseNote->description}}</td>--}}
                        {{--<td>{{$caseNote->title}}</td>--}}
                        {{--<td>--}}
                        {{--<a href="#">Edit</a> | <a href="#" style="color: #cc0000;">Delete</a>--}}
                        {{--</td>--}}
                        {{--</tr>--}}
                    @endif
                </table>
            </div>
        </div>
    </div>
    {{--<div class="col-md-12">--}}
    {{--<div>--}}
    {{--<div class='table-responsive'>--}}
    {{--<h3>{{$report->type}} Report <br/>--}}
    {{--Date Range: {{$start}} - {{$end}}</h3>--}}
    {{--<table class="table table-hover">--}}
    {{--if there are case notes to report--}}
    {{--@if(count($cases->reportCaseNotes) != 0)--}}
    {{--<tr>--}}
    {{--<th>Premise</th>--}}
    {{--<th>Hours Monitoring Premise</th>--}}
    {{--<th>Guard Presence at Location</th>--}}
    {{--<th>Date</th>--}}
    {{--<th>Case Title</th>--}}
    {{--<th>Case Description</th>--}}
    {{--</tr>--}}
    {{--for the first record, display more data--}}
    {{--<tr>--}}
    {{--<td>{{$cases->location}}</td>--}}
    {{--<td>{{$cases->reportCases->total_hours}}</td>--}}
    {{--<td>{{$cases->reportCases->total_guards}}</td>--}}
    {{--<td>{{$cases->reportCaseNotes[0]->case_date}}</td>--}}
    {{--<td>{{$cases->reportCaseNotes[0]->description}}</td>--}}
    {{--<td>{{$cases->reportCaseNotes[0]->title}}</td>--}}
    {{--</tr>--}}
    {{--for all the other case notes:--}}
    {{--@for($i=1; $i < count($cases->reportCaseNotes); $i++)--}}
    {{--<tr>--}}
    {{--<td></td>--}}
    {{--<td></td>--}}
    {{--<td></td>--}}
    {{--<td>{{$cases->reportCaseNotes[$i]->case_date}}</td>--}}
    {{--<td>{{$cases->reportCaseNotes[$i]->description}}</td>--}}
    {{--<td>{{$cases->reportCaseNotes[$i]->title}}</td>--}}
    {{--</tr>--}}
    {{--@endfor--}}

    {{--@else--}}
    {{--<tr>--}}
    {{--<th>Location</th>--}}
    {{--<th>Total Hours Monitoring Location</th>--}}
    {{--<th>Guard Presence at Location (?? or list the names of the guards??)</th>--}}
    {{--<th>Actions</th>--}}
    {{--</tr>--}}
    {{--<tr>--}}
    {{--<td>{{$cases->location}}</td>--}}
    {{--<td>{{$cases->reportCases->total_hours}}</td>--}}
    {{--<td>{{$cases->reportCases->total_guards}}</td>--}}

    {{--<td>{{$caseNote->description}}</td>--}}
    {{--<td>{{$caseNote->title}}</td>--}}
    {{--<td>--}}
    {{--<a href="#">Edit</a> | <a href="#" style="color: #cc0000;">Delete</a>--}}
    {{--</td>--}}
    {{--</tr>--}}
    {{--@endif--}}
    {{--</table>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
@stop
