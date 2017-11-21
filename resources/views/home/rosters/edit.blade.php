@extends('layouts.master_layout_recent_date')
@extends('sidebar')

@section('title-item')
    Edit Shift
@stop

@section('custom-scripts-body')
    <script>

        function checkAmt() {

            var values = $('#mySelect').val();
            var elem = document.getElementById("checks_amt");

            if (values.length > 1) {
                if (elem.hasAttribute('disabled')) {
                    elem.removeAttribute("disabled");

                }
            } else if (values.length == 1) {

                if (!elem.hasAttribute('disabled')) {

                    elem.setAttribute("disabled", "disabled");
                    alert('For shifts with single locations, the checks amount will take the default of 1 check');//fixme improve a div on screen instead
                }
            }
        }

    </script>
@stop

@section('page-content')
    <div class='col-md-8 form-pages'>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div>
            {{--$assigned is an array with some values the same for all items, but location and employee details differ. --}}
            {{--Assigned_shift_id and date and time values are the same for all items therefore, just access the first items details for these fields--}}
            {{--{{ Form::model($assigned, array('route' => array('rosters.update', $assigned[0]->assigned_shift_id), 'method' => 'PUT')) }}--}}
            {{ Form::open(['route' => ['rosters.update', $assigned[0]->assigned_shift_id], 'method'=>'put']) }}

            <div class='form-group'>
                {!! Form::Label('title', 'Shift Title:') !!}
                {{ Form::text('title', $assigned[0]->shift_title, array('class' => 'form-control', 'placeholder' => 'eg University Grounds Security', 'onkeypress'=>'return noenter()')) }}
            </div>

            <div class='form-group'>
                {!! Form::Label('desc', 'Shift Description:') !!}
                {{ Form::text('desc', $assigned[0]->shift_description, array('class' => 'form-control', 'placeholder' => 'eg Provide security services at the University of Texas at Austin', 'onkeypress'=>'return noenter()')) }}
            </div>

            <div class='form-group'>
                {{ Form::label('startDate', 'Start Date') }}
                {{ Form::text('startDateTxt', $startDate, array('class' => 'datepicker')) }}
                &nbsp;&nbsp;&nbsp;
                {{ Form::label('startTime', 'Start Time') }}
                <input class="input-a" value={{$startTime}} name="startTime"
                       data-default={{$startTime}} placeholder={{$startTime}}>
                @include('clock-picker')
            </div>

            <div class='form-group'>
                {{ Form::label('endDate', 'End Date&nbsp;&nbsp;') }}
                {{ Form::text('endDateTxt', $endDate, array('class' => 'datepicker')) }}
                &nbsp;&nbsp;&nbsp;
                {{ Form::label('endTime', 'End Time&nbsp;&nbsp;') }}
                <input class="input-b" value={{$endTime}} name="endTime"
                       data-default={{$endTime}} placeholder={{$endTime}}>
                @include('clock-picker')
            </div>

            <div class='form-group'>
                {!! Form::Label('locations', 'Select Location:') !!}
                (Tip: ctrl/cmd to select more than one)

                <select class="form-control" name="locations[]" multiple="multiple" size="auto"
                        id="mySelect" onFocusOut="checkAmt()" onkeypress="return noenter()">
                    {{--Highlight assigned shift locations--}}
                    @foreach($myLocations as $myLocation)
                        <option value="{{$myLocation->location_id}}" selected
                                multiple>{{$myLocation->location}}</option>
                    @endforeach
                    {{--List all the locations besides those that are selected at the top of the list because stored in db--}}
                    @foreach($locList as $loc)
                        @if($loc->id != $myLocation->location_id)
                            <option value="{{$loc->id}}">{{$loc->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class='form-group'>
                {!! Form::Label('employees', 'Select Employee:') !!}

                <select class="form-control" name="employees[]" multiple="multiple" size="auto"
                        onkeypress="return noenter()">
                    {{--Highlight assigned_shift_employees--}}
                    @foreach($myEmployees as $myEmployee)
                        <option value="{{$myEmployee->mobile_user_id}}" selected
                                multiple>{{$myEmployee->employee}}</option>
                    @endforeach
                    {{--List all the employees besides those that are selected at the top of the list because stored in db--}}
                    @foreach($empList as $emp)
                        @if($emp->user_id != $myEmployee->mobile_user_id)
                            <option value="{{$emp->user_id}}">{{$emp->first_name}} {{$emp->last_name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class='form-group'>
                {!! Form::Label('checks', 'Number of Visits Required:') !!}
                {{--apply disabled to the checks field if only 1 location has been stored in the db--}}
                @if(count($myLocations) > 1)
                    {{ Form::text('checks', $assigned[0]->checks, array('class' => 'form-control',
                    'id' => 'checks_amt')) }}

                @elseif(count($myLocations) == 1)
                    {{ Form::text('checks', $assigned[0]->checks, array('class' => 'form-control',
                    'id' => 'checks_amt', 'disabled' => 'disabled')) }}

                @endif


            </div>

            <div class='form-group form-buttons'>
                {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
                <a href="/rosters" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
            </div>
            {{ Form::close() }}
        </div>

    </div>
@stop


{{--@section('page-content')--}}
{{--<div class='col-lg-4 col-lg-offset-4 form-pages'>--}}

{{--@if (count($errors) > 0)--}}
{{--<div class="alert alert-danger">--}}
{{--<ul>--}}
{{--@foreach ($errors->all() as $error)--}}
{{--<li>{{ $error }}</li>--}}
{{--@endforeach--}}
{{--</ul>--}}
{{--</div>--}}
{{--@endif--}}
{{--<div class='col-lg-4 col-lg-offset-4 form-pages col-md-8'>--}}
{{--{{ Form::model($job, array('route' => array('rosters.update', $job->id), 'method' => 'PUT')) }}--}}
{{--<div class='form-group'>--}}
{{--{!! Form::Label('employees', 'Select Employee:') !!}--}}
{{--<select class="form-control" name="assigned_user_id">--}}
{{--for the employee associated with the shift being edited, display at the top of the select list--}}
{{--@if($employee != null)--}}
{{--<option value="{{$employee->id}}" selected>{{$employee->first_name}} {{$employee->last_name}}</option>--}}
{{--@endif--}}
{{--for all other items:--}}
{{--@foreach($empList as $emp)--}}
{{--@if(($emp->id != $job->assigned_user_id)||($employee == null))--}}
{{--<option value="{{$emp->id}}">{{$emp->first_name}} {{$emp->last_name}}</option>--}}
{{--@endif--}}
{{--@endforeach--}}
{{--</select>--}}
{{--</div>--}}

{{--<div class='form-group'>--}}
{{--{!! Form::Label('locations', 'Select Location:') !!}--}}
{{--<select class="form-control" name="locations">--}}
{{--<option value="{{$locationName}}" selected>{{$locationName}}</option>--}}
{{--@foreach($locList as $loc)--}}
{{--@if($loc->name != $locationName)--}}
{{--<option value="{{$loc->name}}">{{$loc->name}}</option>--}}
{{--@endif--}}
{{--@endforeach--}}
{{--</select>--}}
{{--</div>--}}

{{--<div class='form-group'>--}}
{{--{!! Form::Label('checks', 'Number of Visits Required:') !!}--}}
{{--{{ Form::text('checks', null, array('class' => 'form-control')) }}--}}
{{--</div>--}}

{{--<div class='form-group'>--}}
{{--{{ Form::label('startDate', 'Start Date') }}--}}
{{--{{ Form::text('startDateTxt', '', array('class' => 'datepicker')) }}--}}
{{--&nbsp;&nbsp;&nbsp;--}}
{{--{{ Form::label('startTime', 'Start Time') }}--}}
{{--<input class="input-a" value="" name="startTime" data-default="12:30">--}}
{{--@include('clock-picker')--}}
{{--</div>--}}

{{--<div class='form-group'>--}}
{{--{{ Form::label('endDate', 'End Date&nbsp;&nbsp;&nbsp;') }}--}}
{{--{{ Form::text('endDateTxt', '', array('class' => 'datepicker')) }}--}}
{{--&nbsp;&nbsp;&nbsp;--}}
{{--{{ Form::label('endTime', 'End Time&nbsp;&nbsp;&nbsp;') }}--}}
{{--<input class="input-b" value="" name="endTime" data-default="20:30">--}}
{{--@include('clock-picker')--}}
{{--</div>--}}

{{--<div class='form-group'>--}}
{{--{{ Form::submit('Update', ['class' => 'btn btn-success']) }}--}}
{{--<a href="/rosters" class="btn btn-info" style="margin-right: 3px;">Cancel</a>--}}
{{--</div>--}}
{{--{{ Form::close() }}--}}
{{--</div>--}}

{{--</div>--}}
{{--@stop--}}

