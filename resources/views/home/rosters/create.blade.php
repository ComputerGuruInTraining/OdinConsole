@extends('layouts.master_layout_recent_date')
@include('sidebar')

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

    {{ Form::open(['role' => 'form', 'url' => '/rosters']) }}
    <div class='form-pages col-md-8' >
        <div class='form-group'>
            {!! Form::Label('title', 'Shift Title:') !!}
            {{ Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'eg University Grounds Security', 'onkeypress'=>'return noenter()')) }}
        </div>

        <div class='form-group'>
            {!! Form::Label('desc', 'Shift Description:') !!}
            {{ Form::text('desc', null, array('class' => 'form-control', 'placeholder' => 'eg Provide security services at the University of Texas at Austin', 'onkeypress'=>'return noenter()')) }}
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

        {{--TODO: v2 tip with info icon and then press for this tip
        FIXME: jquery scripts interfering with the operation of popover tip --}}

        {{--<div style="color: #dd4b39; padding-bottom: 8px;">--}}
            {{--Tip: Hold down ctrl/cmd to add more than one employee or location. Release ctrl/cmd to scroll the list.--}}
        {{--</div>--}}

        <div class='form-group'>
            {!! Form::Label('employees', 'Select Employee (Tip: ctrl/cmd to select more than one):') !!}
            <select class="form-control" name="employees[]" multiple="multiple" size="auto" onkeypress="return noenter()">
                @foreach($empList as $emp)
                    <option value="{{$emp->user_id}}">{{$emp->first_name}} {{$emp->last_name}}</option>

                @endforeach
            </select>
        </div>
        <div class='form-group'>
            {!! Form::Label('locations', 'Select Location:') !!}
            <select class="form-control" multiple="multiple" name="locations[]" size="10" onkeypress="return noenter()">
                @foreach($locList as $loc)
                    <option value="{{$loc->id}}">{{$loc->name}}</option>
                @endforeach
            </select>
        </div>

        <div class='form-group'>
            {!! Form::Label('checks', 'Number of Visits Required:') !!}
            {{ Form::text('checks', 1, array('class' => 'form-control')) }}
        </div>

        <div class='form-group form-buttons'>
            {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
            <a href="/rosters" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        </div>

        {{ Form::close() }}

    </div>
@stop