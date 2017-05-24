@extends('layouts.list')
@extends('sidebar_custom')

@section('title')
    Rosters
@stop

@section('custom-menu-title')
    Roster
@stop

@section('create-link')
    "/rosters/create"
@stop

@section('title-item')
    Most Recent Roster or ??
@stop
@section('content-item')
    <table>

        <tr class="details-tr">
            <td class="item-details">Shift Date:</td>
            <td>{{$selected->job_scheduled_for}}</td>
        </tr>
        <tr class="details-tr">
            <td class="item-details">Shift Duration:</td>
            <td>{{$selected->estimated_job_duration}}</td>
        </tr>
        <tr class="details-tr">
            <td class="item-details">Location:</td>
            <td>{{$selected->locations}}</td>
        </tr>
        <tr class="details-tr">
            <td class="item-details">Visits Required:</td>
            <td>{{$selected->checks}}</td>
        </tr>
        <tr class="details-tr">
            <td class="item-details">Employee:</td>
            <td>{{$selected->assigned_user_id}}</td>
        </tr>
    </table>
    <div>
        <a href="/rosters/{{$selected->id}}/edit" class="btn btn-info" style="margin-right: 3px;">Edit Shift</a>
        <a href="/confirm-delete-shift/{{$selected->id}}/" class="btn btn-danger" style="margin-right: 3px;">Delete Shift</a>
    </div>
@stop

@section('title-list')
    Rosters
@stop

@section('content-list')
    <div style="padding:15px 0px 10px 0px;">
        <button type="button" class="btn btn-success" onclick="window.location.href='rosters/create'">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Shift
        </button>
    </div>
    @foreach($jobs as $job)
        <div class="list"><a href="/rosters/{{ $job['id'] }}">{{$job['locations']}}</a></div>
        <br>
    @endforeach
@stop



