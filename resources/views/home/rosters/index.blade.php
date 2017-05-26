@extends('layouts.master_layout')
@extends('sidebar_custom')

{{--@section('title')--}}
{{--Rosters--}}
{{--@stop--}}

@section('custom-menu-title')
    Roster
@stop

@section('create-link')
    "/rosters/create"
@stop

{{--@section('title-item')--}}
{{--Most Recent Roster or ??--}}
{{--@stop--}}
{{--@section('content-item')--}}
{{--<p>Display most recently created roster here???? OR Create button?? or ??</p>--}}
{{--<table>--}}
{{--<tr class="details-tr">--}}
{{--<td class="item-details">Roster Name:</td>--}}
{{--<td></td>--}}
{{--</tr>--}}
{{--<tr class="details-tr">--}}
{{--<td class="item-details">Roster Date:</td>--}}
{{--<td></td>--}}
{{--</tr>--}}
{{--<tr class="details-tr">--}}
{{--<td class="item-details">Roster Details eg Employee Name and Location:</td>--}}
{{--<td></td>--}}
{{--</tr>--}}
{{--</table>--}}
{{--<div>--}}
{{--<p>Btn or just link in sidebar??</p>--}}
{{--<a href="/rosters/create" class="btn btn-info" style="margin-right: 3px;">Add Shift</a>--}}
{{--<a href="/rosters/76/edit" class="btn btn-info" style="margin-right: 3px;">Edit Shift</a>--}}
{{--<a href="/confirm-delete-shift/76/" class="btn btn-danger" style="margin-right: 3px;">Delete Shift</a>--}}
{{--<a href="#" class="btn btn-info" style="margin-right: 3px;">Edit</a>--}}
{{--<a href="#" class="btn btn-danger" style="margin-right: 3px;">Delete</a>--}}
{{--</div>--}}
{{--@stop--}}

@section('title-item')
    Rosters
@stop

@section('page-content')
    <div class="col-md-12">

        <div style="padding:15px 0px 10px 0px;">
            <button type="button" class="btn btn-success" onclick="window.location.href='rosters/create'">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Shift
            </button>
        </div>
        {{--TODO low, v2 or v1 after other tasks implemented: if no checks, input 0--}}
        <div class='table-responsive'>
            <table class="table  table-hover list-view">
                <tr>
                    <th>Start Date</th>

                    <th>Locations (Checks)</th>
                    <th>Assigned to</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
                @foreach($jobs as $job)
                    @if($job->uniqueDate != null)
                        @foreach($groupJobs->get($job->uniqueDate) as $shift)
                            {{--@if((($job->key)%2) == 0)--}}
                                {{--@foreach($groupJobs as $index => $value)--}}
                                <tr class="unique-row">
                                    <td rowspan="{{count($shift->uniqueDate == $shift->startDate)}}">{{$shift->uniqueDate}}</td>
                                    @if($shift->locations != null)
                                        <td >{{$shift->locations}} ({{$shift->checks}})</td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>{{$shift->employeeName}}</td>
                                    @if($shift->endTime != null)
                                        <td>{{$shift->startTime}}-{{$shift->endTime}}</td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>
                                        <a href="/rosters/{{ $shift->id }}">View</a> | <a
                                                href="/rosters/{{$shift->id}}/edit">Edit</a>
                                    </td>
                                </tr>
                            {{--@elseif((($job->key)%2) != 0)--}}
                                {{--<tr>--}}
                                    {{--<td>{{$shift->uniqueDate}}</td>--}}
                                    {{--@if($shift->locations != null)--}}
                                        {{--<td>{{$shift->key}} ({{$shift->checks}})</td>--}}
                                    {{--@else--}}
                                        {{--<td></td>--}}
                                    {{--@endif--}}
                                    {{--<td>{{$shift->employeeName}}</td>--}}
                                    {{--@if($shift->endTime != null)--}}
                                        {{--<td>{{$shift->startTime}}-{{$shift->endTime}}</td>--}}
                                    {{--@else--}}
                                        {{--<td></td>--}}
                                    {{--@endif--}}
                                    {{--<td>--}}
                                        {{--<a href="/rosters/{{ $shift->id }}">View</a> | <a--}}
                                                {{--href="/rosters/{{$shift->id}}/edit">Edit</a>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--@endif--}}

                        @endforeach
                        {{--@elseif(($job->uniqueDate != null)&&($job->key%2 != 0))--}}
                        {{--@foreach($groupJobs->get($job->uniqueDate) as $shift)--}}
                        {{--@foreach($groupJobs as $index => $value)--}}
                        {{----}}
                        {{--@endforeach--}}
                    @endif

                @endforeach
            </table>
        </div>
    </div>
@stop

{{--@foreach($jobs as $index => $job)--}}
{{--@if($job['uniqueDate'] != null)--}}
{{--<tr class="unique-row">--}}
{{--@if($job['uniqueDate'] != null)--}}
{{--<td>{{}}</td>--}}

{{--@if($job['locations'] != null)--}}
{{--@if($index == locations)--}}
{{--<td>{{$job['locations']}} ({{$job['checks']}})</td>--}}
{{--<td>{{$job['locations']}} ({{$job['checks']}})</td>--}}
{{--@else--}}
{{--<td></td>--}}
{{--@endif--}}
{{--<td>{{$job['employeeName']}}</td>--}}
{{--@if($job['endTime'] != null)--}}
{{--<td>{{$job['startTime']}}-{{$job['endTime']}}</td>--}}
{{--@else--}}
{{--<td></td>--}}
{{--@endif--}}
{{--<td>--}}
{{--<a href="/rosters/{{ $job['id'] }}">View</a> | <a href="/rosters/{{$job['id']}}/edit">Edit</a>--}}
{{--</td>--}}
{{--</tr>--}}
{{--@else--}}
{{--<tr>--}}
{{--<td></td>--}}
{{--@if($job['locations'] != null)--}}
{{--<td>{{$job['locations']}} ({{$job['checks']}})</td>--}}
{{--@else--}}
{{--<td></td>--}}
{{--@endif--}}
{{--<td>{{$job['employeeName']}}</td>--}}
{{--@if($job['endTime'] != null)--}}
{{--<td>{{$job['startTime']}}-{{$job['endTime']}}</td>--}}
{{--@else--}}
{{--<td></td>--}}
{{--@endif--}}
{{--<td>--}}
{{--<a href="/rosters/{{ $job['id'] }}">View</a> | <a href="/rosters/{{$job['id']}}/edit">Edit</a>--}}
{{--</td>--}}
{{--</tr>--}}
{{--@endif--}}-
{{--@endforeach--}}







