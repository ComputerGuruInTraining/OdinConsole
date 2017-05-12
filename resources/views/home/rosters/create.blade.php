@extends('layouts.master_layout')

@extends('sidebar_custom')


@section('title')
    Create Roster
@stop

@section('title-item')
    Create Roster
@stop

@section('page-content')
    <div class='col-lg-4 col-lg-offset-4 form-pages'>
        <P>
            Choose employee (one or more at that time at the locations)
            Choose location (one or more)
          ????**** Number of Visits Required?????
        </P>
        {{ Form::open(['role' => 'form', 'url' => '/rosters']) }}
        {{--{{ Form::model($empList, ['url' => '/roster', 'method' => 'post']) , 'method' => 'POST'}}--}}
        <div class='form-group'>
            {!! Form::Label('employees', 'Select Employee:') !!}
            <select class="form-control" name="employee_id">
                @foreach($empList as $emp)
                    <option value="{{$emp->id}}">{{$emp->first_name}}</option>
                @endforeach
            </select>
        </div>

        <div class='form-group'>
            {!! Form::Label('locations', 'Select Location:') !!}
            <select class="form-control" name="location_id">
                @foreach($locList as $list)
                    <option value="{{$list->id}}">{{$list->name}}</option>
                @endforeach
            </select>
        </div>

        <div class='form-group'>
            {{ Form::label('startDate', 'Start Date') }}
            {{ Form::text('startDateTxt', '', array('class' => 'datepicker')) }}
        </div>

        <div class='form-group'>
            {{ Form::label('endDate', 'End Date') }}
            {{ Form::text('endDateTxt', '', array('class' => 'datepicker')) }}
        </div>
        <div class='form-group'>
            {{ Form::label('startTime', 'Start Time') }}
                <div class="container">
                    <input class="input-a" value="" name="startTime" data-default="12:30">
                </div>
            @include('clock-picker')
        </div>
        <div class='form-group'>
            {{ Form::label('endTime', 'End Time') }}
                <div class="container">
                    <input class="input-b" value="" name="endTime" data-default="20:30">
                </div>
            @include('clock-picker')
        </div>

        <div class='form-group'>
            {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
            {{ Form::button('Cancel', ['class' => 'btn btn-primary']) }}
        </div>



        {{ Form::close() }}

    </div>
@stop


