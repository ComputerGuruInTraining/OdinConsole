@extends('layouts.master_layout_recent_date')
@include('sidebar')

{{--@section('title')--}}
{{--Create Roster--}}
{{--@stop--}}

@section('title-item')
    Generate Report
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

    <div class='form-pages col-md-8'>

        {{ Form::open(['role' => 'form', 'url' => '/reports']) }}

        <div class='form-group'>
            {{ Form::label('dateFrom', 'Start Date of Report:') }}
            {{ Form::text('dateFrom', '', array('class' => 'datepicker', 'onkeypress'=>'return noenter()')) }}
        </div>

        <div class='form-group'>
            {{ Form::label('dateTo', 'End Date of Report: &nbsp;&nbsp;') }}
            {{ Form::text('dateTo', '', array('class' => 'datepicker',  'onkeypress'=>'return noenter()')) }}
        </div>

        <div class='form-group'>
            {!! Form::Label('locations', 'Select Location:') !!}
            <select class="form-control" name="location" onkeypress="return noenter()">
                @foreach($locations as $loc)
                    <option value="{{$loc->id}}">{{$loc->name}}</option>
                @endforeach
            </select>
        </div>
        <div class='form-group'>
            {!! Form::Label('type', 'Select Activity:') !!}
            <select class="form-control" name="type" onkeypress="return noenter()">
                {{--report types provided by our app--}}
                {{--Case Notes for a Location over a period--}}
                <option value="Case Notes">Case Notes</option>
                <option value="Location Checks">Location Checks</option>
                {{--Exact Times the premise was visited by a guard over a period--}}
                {{--<option value="caseNotes">Guard Patrols</option>--}}
            </select>
        </div>

        <div class='form-group form-buttons'>
            {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
            <a href="/reports" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        </div>

        {{ Form::close() }}

    </div>
@stop


