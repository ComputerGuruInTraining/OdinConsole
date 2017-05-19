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

{{--@section('page-content')--}}
    {{--@foreach($jobs->sortBy('locations') as $job)--}}
        {{--<div class="list"><a href="/rosters/{{ $job->id }}">{{$job->locations}}</a></div>--}}
        {{--<br>--}}
    {{--@endforeach--}}
{{--@stop--}}

@section('page-content')
    <div class="col-md-12">

        <div style="padding:15px 0px 10px 0px;">
            <button type="button" class="btn btn-success" onclick="window.location.href='rosters/create'">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Shift
            </button>
        </div>
{{--TODO low, v2 or v1 after other tasks implemented: if no checks, input 0--}}
        <div class='table-responsive'>
            <table class="table  table-hover">
                <tr>
                    <th>Assigned to</th>
                    <th>Locations</th>
                    <th>Visits Required</th>
                    <th>Start Date</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
                @foreach($jobs->sortBy('locations') as $job)
                    <tr>
                        <td>{{$job->assigned_user_id}}</td>
                        <td>{{$job->locations}}</td>
                        <td>{{$job->checks}}</td>
                        <td>{{$job->job_scheduled_for}}</td>
                        <td>{{$job->estimated_job_duration}}</td>
                        <td>
                            <a href="/rosters/{{ $job->id }}">View</a> | <a href="/rosters/{{$job->id}}/edit">Edit</a>
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
    </div>
@stop





