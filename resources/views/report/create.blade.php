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
        {{ Form::open(['role' => 'form', 'url' => '/rosters']) }}
        <div class='form-group'>
            {{ Form::label('startDate', 'Start Date') }}
            {{ Form::text('startDateTxt', '', array('class' => 'datepicker', 'onkeypress'=>'return noenter()')) }}
        </div>
        <div class='form-group'>
            {{ Form::label('endDate', 'End Date&nbsp;&nbsp;&nbsp;') }}
            {{ Form::text('endDateTxt', '', array('class' => 'datepicker',  'onkeypress'=>'return noenter()')) }}
        </div>
        <div class='form-group'>
            {!! Form::Label('locations', 'Select Location:') !!}
            <select class="form-control" multiple="multiple" name="locations[]" size="10" onkeypress="return noenter()">
            <option value="" selected disabled>Select Location:</option>
            @foreach($locList as $loc)
            <option value="{{$loc->name}}">{{$loc->name}}</option>
            @endforeach
            </select>
        </div>
        <div class='form-group'>
            {!! Form::Label('employees', 'Select Employee:') !!}
            <select class="form-control" name="employees[]" multiple="multiple" size="auto" onkeypress="return noenter()">
                @foreach($empList as $emp)
                    <option value="{{$emp->id}}">{{$emp->first_name}} {{$emp->last_name}}</option>
                @endforeach
            </select>
        </div>


        {{--<div class='form-group'>--}}
            {{--{!! Form::Label('checks', 'Number of Visits Required:') !!}--}}
            {{--{{ Form::text('checks', null, array('class' => 'form-control')) }}--}}
        {{--</div>--}}




        <div class='form-group'>
            {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
            <a href="/reports" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        </div>

        {{ Form::close() }}

    </div>
@stop


