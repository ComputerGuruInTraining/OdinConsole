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
        {{--TODO low, v2 or v1 after other tasks implemented: if only 1 check, input 1--}}
        <div class='table-responsive'>
            <table class="table table-hover">
                <tr>
                    <th>Start Date</th>
                    <th>Locations (Checks)</th>
                    <th>Time</th>
                    <th>Assigned to</th>
                    <th>Actions</th>
                </tr>
                @foreach($groupJobs as $index => $formattedJob)
                    <tbody class="group-list">
                    @foreach ($groupJobs->get($index) as $shift)
                        @if($index == $shift->startDate)
                            <tr class="group-table">
                                <td>{{$shift->uniqueDate}}</td>
                                {{--TODO: v2 or lower v1: group locations for better display--}}
                                @if($shift->uniqueLocations != null)<!--locations is null if duplicate location-->
                                    <td class="group-data">{{$shift->uniqueLocations}} ({{$shift->checks}})</td>
                                @else
                                <td></td>
                                @endif
                                @if($shift->endTime != null)<!--endTime is null if duplicate startTime and endTime-->
                                    <td>{{$shift->startTime}}-{{$shift->endTime}}</td>
                                @else
                                    <td></td>
                                @endif
                                <td>{{$shift->employeeName}}</td>
                                <td>
                                    <a href="/rosters/{{$shift->id}}/edit">Edit</a> | <a href="/confirm-delete-shift/{{$shift->id}}/" style="color: #cc0000;">Delete</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
@stop






