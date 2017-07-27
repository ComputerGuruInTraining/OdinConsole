@extends('layouts.master_layout')
@extends('sidebar')

@section('custom-menu-title')
    Roster
@stop

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
        <div class='table-responsive'>
            <table class="table table-hover">
                <tr>
                    <th>Start Date</th>
                    <th>Locations (Checks)</th>
                    <th>Time</th>
                    <th>Assigned to</th>
                    <th>Actions</th>
                </tr>
                @foreach($assigned as $index => $formattedShift)
                    <tbody class="group-list">
                    {{--<tr class="row-heading"><td>{{$formattedShift->shift_title}}</td><td></td><td></td><td></td><td></td></tr>--}}
                    @foreach ($assigned->get($index) as $shift)
                        @if($index == $shift->assigned_shift_id)
                           <!--ensure the location associated with the shift is still in the db to catch for errors-->
                                <tr class="group-table">
                                    
                                    <td>{{$shift->unique_date}}</td>
                                    @if($shift->unique_locations != null)<!--locations is null if duplicate location-->
                                        <td class="group-data">{{$shift->unique_locations}} ({{$shift->checks}})</td>
                                    @else
                                    <td></td>
                                    @endif
                                    @if($shift->end_time != null)<!--endTime is null if duplicate startTime and endTime-->
                                        <td>{{$shift->start_time}}-{{$shift->end_time}}</td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>{{$shift->employee}}</td>
                                    <td>
                                        <a href="/rosters/{{$shift->assigned_shift_id}}/edit">Edit</a> | <a href="/confirm-delete/{{$shift->assigned_shift_id}}/{{$url}}" style="color: #cc0000;">Delete</a>
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






