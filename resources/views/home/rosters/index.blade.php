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
                    <th>Time</th>
                    <th>Locations</th>
                    <th>Assigned to</th>
                    <th>Actions</th>
                </tr>
                @foreach($assigned as $index => $formattedShift)
                    <tr class="row-heading"><td>{{$formattedShift[0]->shift_title}}</td><td></td><td></td><td></td><td></td></tr>
                    <tbody class="group-list">
                    <tr>
                        <td>{{$formattedShift[0]->unique_date}}</td>
                    @if($formattedShift[0]->end_time != null)<!--endTime is null if duplicate startTime and endTime-->
                        <td>{{$formattedShift[0]->start_time}}-{{$formattedShift[0]->end_time}}</td>
                    @endif
                    @if($formattedShift[0]->unique_locations != null)<!--locations is null if duplicate location-->
                        <td class="group-data">{{$formattedShift[0]->unique_locations}} {{$formattedShift[0]->checks}}</td>
                        @endif
                        <td>{{$formattedShift[0]->unique_employees}}</td>
                        <td>
                            <a href="/rosters/{{$formattedShift[0]->assigned_shift_id}}/edit">Edit</a> | <a href="/confirm-delete/{{$formattedShift[0]->assigned_shift_id}}/{{$url}}" style="color: #cc0000;">Delete</a>
                        </td>
                    </tr>
                    @foreach ($assigned->get($index) as $shift)
                        {{--@if(($shift->unique_locations != null)&&($shift->unique_employees != null))--}}

                            <tr>
                                <td></td>
                                <td></td>
                                {{--@if($formattedShift[0]->unique_locations != $shift->unique_locations)--}}
                                    <td>{{$shift->unique_locations}}</td>
                                {{--@endif--}}
                                {{--@if($formattedShift[0]->unique_employees != $shift->unique_employees)--}}
                                    <td>{{$shift->unique_employees}}</td>
                                {{--@endif--}}
                                <td></td>
                            </tr>
                        {{--@elseif(($shift->unique_locations != null)&&($shift->unique_employees == null))--}}
                            {{--@if($formattedShift[0]->unique_locations != $shift->unique_locations)--}}

                                {{--<tr><td></td><td></td><td>{{$shift->unique_locations}}</td><td></td><td></td></tr>--}}
                            {{--@endif--}}
                        {{--@elseif(($shift->unique_locations == null)&&($shift->unique_employees != null))--}}
                            {{--@if($formattedShift[0]->unique_employees != $shift->unique_employees)--}}

                                {{--<tr><td></td><td></td><td></td><td>{{$shift->unique_employees}}</td><td></td></tr>--}}
                            {{--@endif--}}
                        {{--@endif--}}
                    @endforeach
                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
@stop






