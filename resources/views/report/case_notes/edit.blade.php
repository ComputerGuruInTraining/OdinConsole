@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')

@stop

@section('page-content')
    <div class="col-md-12">
        <div>
            <div class='table-responsive'>
                <h3 class="report-title" id="report-heading">Manage Report {{$report->type}} </h3>
                {{--<div class="report-header">--}}
                <table class="col-md-12 margin-bottom">
                    <tr><h4 id="report-date">{{$start}} - {{$end}}</h4></tr>
                    <tr class="report-header-row">
                        <td>Premise:</td>
                        </td>
                        <td class="report-header">{{$cases->location->name}}</td>
                    </tr>
                    {{--<tr class="report-header-row"><td>Hours Monitoring Premise:</td><td class="report-header"> {{$cases->reportCases->total_hours}}</td></tr>--}}
                    <tr class="report-header-row">
                        <td>Guard Presence at Location:</td>
                        <td class="report-header">{{$cases->reportCases->total_guards}}</td>
                    </tr>
                    {{--</div>--}}
                </table>

                <table class="table table-hover">
                    {{--if there are case notes to report--}}
                    <tr>
                        <th>Date</th>
                        {{--<th>Time</th>--}}
                        <th>Case Title</th>
                        <th>Case Description</th>
                        <th>Case Image</th>
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
                                    {{--                                    <td>{{$item->case_time}}</td>--}}
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->description}}</td>
                                    <td>{{$item->hasImg}}</td>
                                    <td>{{$item->employee}}</td>
                                    @if($item->title != "Nothing to Report")

                                        <td><a href="/edit-case-notes/{{$item->id}}/reports/{{$reportId}}" class="edit-links">Edit</a>
                                            |
                                            <a href="/delete/{{$urlCancel}}/{{$item->id}}/{{$reportId}}" style="color: #990000;">Delete</a>
                                        </td>
                                    @else
                                        <td>
                                            <a href="/delete/{{$urlCancel}}/{{$item->id}}/{{$reportId}}" style="color: #990000;">Delete</a>
                                            {{--<a href="/confirm-delete/{{$item->id}}/{{$url}}"--}}
                                           {{--style="color: #990000;">Delete</a>--}}
                                        </td>
                                    @endif
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
                </table>
            </div>
        </div>
    </div>

@stop
