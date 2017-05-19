{{--TODO: change to extend a create_layout once layout exists--}}
@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Edit Shift
@stop

@section('page-content')
    <div class='col-lg-4 col-lg-offset-4 form-pages'>

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
            {{ Form::model($job, array('route' => array('rosters.update', $job->id), 'method' => 'PUT')) }}
                <div class='form-group'>
                    {!! Form::Label('employees', 'Select Employee:') !!}
                    <select class="form-control" name="assigned_user_id">
                        @foreach($empList as $emp)
                            <option value="{{$emp->id}}">{{$emp->first_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class='form-group'>
                    {!! Form::Label('locations', 'Select Location:') !!}
                    <select class="form-control" name="locations">
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

                <div class='form-group'>
                    {{ Form::label('startDate', 'Start Date') }}
                    {{ Form::text('startDateTxt', '', array('class' => 'datepicker')) }}
                    &nbsp;&nbsp;&nbsp;
                    {{ Form::label('startTime', 'Start Time') }}
                    <input class="input-a" value="" name="startTime" data-default="12:30">
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
                    {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                    {{ Form::button('Cancel', ['class' => 'btn btn-primary']) }}
                </div>
            {{ Form::close() }}
        </div>

    </div>
@stop

