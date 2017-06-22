@extends('layouts.master_layout')
@include('sidebar')

{{--@section('title')--}}
    {{--Create Roster--}}
{{--@stop--}}

@section('title-item')
    Add Shift to Roster
@stop

@section('page-content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class='col-lg-4 col-lg-offset-4 form-pages col-md-8'>
        <div style="color: #dd4b39; padding-bottom: 8px;">
            {{--TODO: consider having a tip with info icon and then press for this tip:--}}
            Press ctrl to add more than one employee or location.
            Don't press ctrl to scroll the list.
        </div>
        {{ Form::open(['role' => 'form', 'url' => '/rosters']) }}
        <div class='form-group'>
            {!! Form::Label('employees', 'Select Employee:') !!}
            <select class="form-control" name="employees[]" multiple="multiple" size="auto" onkeypress="return noenter()">
                @foreach($empList as $emp)
                    <option value="{{$emp->id}}">{{$emp->first_name}} {{$emp->last_name}}</option>
                @endforeach
            </select>
        </div>
        <div class='form-group'>
            {!! Form::Label('locations', 'Select Location:') !!}
            <select class="form-control" multiple="multiple" name="locations[]" size="10" onkeypress="return noenter()">
                {{--<option value="" selected disabled>Select Location:</option>--}}
                @foreach($locList as $loc)
                    <option value="{{$loc->name}}">{{$loc->name}}</option>
                @endforeach
            </select>
        </div>

        <div class='form-group'>
            {!! Form::Label('checks', 'Number of Visits Required:') !!}
            {{ Form::text('checks', null, array('class' => 'form-control')) }}
        </div>
        <div>
            Important: A new shift can be used to add several employees and locations for the 1 shift,
            but the amount of visits required will be the same for all locations.
        </div>

        <div class='form-group'>
                {{ Form::label('startDate', 'Start Date') }}
                {{ Form::text('startDateTxt', '', array('class' => 'datepicker', 'onkeypress'=>'return noenter()')) }}
                &nbsp;&nbsp;&nbsp;
                {{ Form::label('startTime', 'Start Time') }}
                <input class="input-a" value="" name="startTime" data-default="9:00" >
            @include('clock-picker')
        </div>

        <div class='form-group'>
            {{ Form::label('endDate', 'End Date&nbsp;&nbsp;&nbsp;') }}
            {{ Form::text('endDateTxt', '', array('class' => 'datepicker',  'onkeypress'=>'return noenter()')) }}
            &nbsp;&nbsp;&nbsp;
            {{ Form::label('endTime', 'End Time&nbsp;&nbsp;&nbsp;') }}
            <input class="input-b" value="" name="endTime" data-default="17:00" onkeypress="return noenter()">
            @include('clock-picker')
        </div>


        <div class='form-group'>
            {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
            <a href="/rosters" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        </div>

        {{ Form::close() }}

    </div>
@stop


