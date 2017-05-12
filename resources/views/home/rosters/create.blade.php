@extends('layouts.list')

@extends('sidebar_custom')
{{--FIXME: v1 duplicate html rendering Probably due to use of Resourc Controller, perhaps clock picker causes multiple renders--}}

@section('title')
    Create Roster
@stop

@section('title-item')
    Create Shift
@stop

@section('page-content')
    <div class='col-lg-4 col-lg-offset-4 form-pages col-md-8'>
        {{ Form::open(['role' => 'form', 'url' => '/rosters']) }}
        <div class='form-group'>
            {!! Form::Label('employees', 'Select Employee:') !!}
            <select class="form-control" name="employee_id">
                @foreach($empList as $emp)
                    <option value="{{$emp->id}}">{{$emp->first_name}}</option>
                @endforeach
            </select>
        </div>
{{--TODO lower priority v1: an add button, to add another location
Functionally, it will operate such that:
add button is pressed, a function is called which echos js to create an element on the view
every time add button pressed--}}
        <div class='form-group'>
            {!! Form::Label('locations', 'Select Location:') !!}
            <select class="form-control" name="location_name">
                @foreach($locList as $loc)
                    <option value="{{$loc->name}}">{{$loc->name}}</option>
                @endforeach
            </select>
        </div>

        <div class='form-group'>
            {!! Form::Label('checks', 'Number of Visits Required:') !!}
            <select class="form-control" name="checks">
                @foreach($checks as $check)
                    <option value="{{$check}}">{{$check}}</option>
                @endforeach
            </select>
        </div>

{{--TODO v2: improvement: change padding rather than adding spaces--}}
        <div class='form-group'>
            {{--<div class="container">--}}
                {{ Form::label('startDate', 'Start Date') }}
                {{ Form::text('startDateTxt', '', array('class' => 'datepicker')) }}
                &nbsp;&nbsp;&nbsp;
                {{ Form::label('startTime', 'Start Time') }}
                <input class="input-a" value="" name="startTime" data-default="12:30">
            {{--</div>--}}
            @include('clock-picker')
        </div>

        <div class='form-group'>
            {{ Form::label('endDate', 'End Date&nbsp;&nbsp;&nbsp;') }}
            {{ Form::text('endDateTxt', '', array('class' => 'datepicker')) }}
            &nbsp;&nbsp;&nbsp;
            {{ Form::label('endTime', 'End Time&nbsp;&nbsp;&nbsp;') }}
            <input class="input-b" value="" name="endTime" data-default="20:30">
            @include('clock-picker')
        </div>

        <div class='form-group'>
            {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
            {{ Form::button('Cancel', ['class' => 'btn btn-primary']) }}
        </div>

        {{ Form::close() }}

    </div>
@stop


