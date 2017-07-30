@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')

@stop

@section('page-content')
    <div class="col-md-12">
        <div>
            <div class='table-responsive'>
                <h3 class="report-title" id="report-heading">{{$report->type}} Report</h3>
                {{--<div class="report-header">--}}
                <table class="col-md-12 margin-bottom">
                    <tr><h4 id="report-date">{{$start}} - {{$end}}</h4></tr>
                    <tr class="report-header-row"><td>Premise:</td></td><td class="report-header">{{$cases->location}}</td></tr>
                    <tr class="report-header-row"><td>Hours Monitoring Premise:</td><td class="report-header"> {{$cases->reportCases->total_hours}}</td></tr>
                    <tr class="report-header-row"><td>Guard Presence at Location:</td><td class="report-header">{{$cases->reportCases->total_guards}}</td></tr>
                {{--</div>--}}
                 </table>

            <table class="table table-hover">
                    {{--if there are case notes to report--}}
                    <tr>
                        {{--<th>Premise</th>--}}
                        <th>Date</th>
                        <th>Time</th>
                        <th>Case Title</th>
                        <th>Case Description</th>
                        <th>Reporting Guard</th>
                        <th>Actions</th>
                    </tr>
                    @if(count($cases->reportCaseNotes) != 0)

                        @foreach($groupCases as $index => $note)
                            <tbody class="group-list">

                            <tr>
                            <td class="report-title">{{$index}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @foreach ($groupCases->get($index) as $item)
                                <tr>
                                    <td></td>
                                    <td>{{$item->case_time}}</td>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->description}}</td>
                                    <td>{{$item->employee}}</td>
                                    <td><a href="/case-notes/{{$item->id}}/edit" class="edit-links">Edit</a>
                                        |
                                        <a href="/confirm-delete/{{$item->id}}/{{$url}}" style="color: #990000;">Delete</a>
                                    </td>
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
