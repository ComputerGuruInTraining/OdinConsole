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
                    <th>Time</th>
                    <th>Locations (Checks)</th>
                    <th>Assigned to</th>
                    <th>Actions</th>
                </tr>

                @foreach($formattedJobs as $formattedJob)
                    <tr>
                        <td>{{$formattedJob['uniqueDate']}}</td>
                        @if($formattedJob['endTime'] != null)
                            <td>{{$formattedJob['startTime']}}-{{$formattedJob['endTime']}}</td>
                        @else
                            <td></td>
                        @endif
                        @if($formattedJob['locations'] != null)
                            <td>{{$formattedJob['locations']}} ({{$formattedJob['checks']}})</td>
                        @else
                            <td></td>
                        @endif


                        {{--<td>{{$formattedJob['startTime']}}-{{$formattedJob['endTime']}}</td><!--TODO convert from duration to start time end time following db restructure conversation-->--}}
                        {{--<td>{{$formattedJob['locations']}} ({{$formattedJob['checks']}})</td>--}}
                        <td>{{$formattedJob['employeeName']}}</td>
                        <td>
                            <a href="/rosters/{{ $formattedJob['id'] }}">View</a> | <a href="/rosters/{{$formattedJob['id']}}/edit">Edit</a>
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
    </div>
@stop





