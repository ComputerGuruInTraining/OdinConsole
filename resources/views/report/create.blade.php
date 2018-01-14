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
            {{ Form::label('dateFrom', 'Start Date of Report *') }}
            {{ Form::text('dateFrom', '', array('class' => 'datepicker', 'onkeypress'=>'return noenter()')) }}
        </div>

        <div class='form-group'>
            {{ Form::label('dateTo', 'End Date of Report * &nbsp;') }}
            {{ Form::text('dateTo', '', array('class' => 'datepicker',  'onkeypress'=>'return noenter()')) }}
        </div>

        <div class='form-group'>
            {!! Form::Label('locations', 'Select Location *') !!}
            <select class="form-control" name="location" onkeypress="return noenter()">
                {{--if there is old input--}}
                @if(count(old('location')) > 0)
                    @foreach($locations as $loc)
                        @if(old('location') == $loc->id)
                            {{--mark the old input as selected--}}
                            <option value="{{$loc->id}}" selected>{{$loc->name}}</option>
                        @else
                            {{--other values in list--}}
                            <option value="{{$loc->id}}">{{$loc->name}}</option>
                        @endif
                    @endforeach

                @else

                    @foreach($locations as $loc)
                        <option value="{{$loc->id}}">{{$loc->name}}</option>
                    @endforeach

                @endif
            </select>
        </div>
        <div class='form-group'>
            {!! Form::Label('type', 'Select Activity *') !!}
            <select class="form-control" name="type" onkeypress="return noenter()">
                {{--report types that our app supports generating--}}
                @if(count( old('type')) > 0 )

                    @if(old('type') == 'Case Notes')
                        <option value="Case Notes" selected>Case Notes</option>
                    @else
                        <option value="Case Notes">Case Notes</option>
                    @endif
                    @if(old('type') == 'Location Checks')
                        <option value="Location Checks" selected>Location Checks</option>
                    @else
                        <option value="Location Checks">Location Checks</option>
                    @endif
                    @if(old('type') == 'Client')
                        <option value="Client" selected>Client</option>
                    @else
                        <option value="Client">Client</option>
                    @endif
                    @if(old('type') == 'Management')
                        <option value="Management" selected>Management</option>
                    @else
                        <option value="Management">Management</option>
                    @endif

                @else
                    {{--Case Notes for a Location over a period--}}
                    <option value="Case Notes">Case Notes</option>
                    <option value="Location Checks">Location Checks</option>
                    <option value="Client">Client</option>
                    <option value="Management">Management</option>


                    {{--TODO: Exact Times the premise was visited by a guard over a period--}}
                    {{--<option value="caseNotes">Guard Patrols</option>--}}
                @endif
            </select>
        </div>

        <div class='form-group form-buttons'>
            {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
            <a href="/reports" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        </div>

        {{ Form::close() }}

    </div>
@stop


