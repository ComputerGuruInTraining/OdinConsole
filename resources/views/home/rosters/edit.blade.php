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
                        <option value="{{$employee->id}}">{{$employee->first_name}} {{$employee->last_name}}</option>
                        @foreach($empList as $emp)
                            @if($emp->id != $job->assigned_user_id){
                            <option value="{{$emp->id}}">{{$emp->first_name}} {{$emp->last_name}}</option>
                            }
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class='form-group'>
                    {!! Form::Label('locations', 'Select Location:') !!}
                    <select class="form-control" name="locations">
                        <option value="{{$locationName}}">{{$locationName}}</option>
                        @foreach($locList as $loc)
                            @if($loc->name != $locationName){
                            <option value="{{$loc->name}}">{{$loc->name}}</option>
                            }
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class='form-group'>
                    {!! Form::Label('checks', 'Number of Visits Required:') !!}
                    <select class="form-control" name="checks">
                        <option value="{{$job->checks}}">{{$job->checks}}</option>
                        @foreach($checks as $check)
                            @if($check != $job->checks){
                            <option value="{{$check}}">{{$check}}</option>
                            }
                            @endif
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
                    {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
                    <a href="/rosters" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
                </div>
            {{ Form::close() }}
        </div>

    </div>
@stop

