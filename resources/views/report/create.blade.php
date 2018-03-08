@extends('layouts.master_layout_recent_date')
@include('sidebar')

{{--@section('title')--}}
{{--Create Roster--}}
{{--@stop--}}

@section('title-item')
    Generate Report
@stop

@section('custom-scripts-body')
    <script>

        window.onload = function (){
            enableEntity()
        };

    function enableEntity() {
        var selectObj = document.getElementById("type");

        var strType = selectObj.options[selectObj.selectedIndex].value;

//todo: on change and onselect/onload for when old input.1st test if input wrong this occurs, or
// just if report fails and back btn pressed in browser
            if ((strType == "Client")||(strType == "Management")) {

                var selectLocs = document.getElementById("selectLocations");
                selectLocs.style.display = "block";

                var selectEmps = document.getElementById("selectEmployees");
                selectEmps.style.display = "none";

            }else if(strType == "Individual"){

                var selectEmps = document.getElementById("selectEmployees");
                selectEmps.style.display = "block";


                var selectLocs = document.getElementById("selectLocations");
                selectLocs.style.display = "none";
            }
    }

    </script>

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
            {!! Form::Label('type', 'Select Activity *') !!}
            <select id="type" class="form-control" name="type" onkeypress="return noenter()" onchange="enableEntity()" >
                {{--report types that our app supports generating--}}
                @if(count( old('type')) > 0 )
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
                    @if(old('type') == 'Individual')
                        <option value="Individual" selected>Individual</option>
                    @else
                        <option value="Individual">Individual</option>
                    @endif

                @else
                    <option value="Client">Client</option>
                    <option value="Management">Management</option>
                    <option value="Individual">Individual</option>
                @endif
            </select>
        </div>

        <div class="alert alert-warning alert-custom">
            <strong>Tip:</strong> Select Activity, and then, based on your selection you will be prompted to select a Location or Employee
        </div>

        <div class='form-group' id="selectLocations" style="display: none;">
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

        <div class='form-group' id="selectEmployees" style="display: none;">
            {!! Form::Label('employees', 'Select Employee *') !!}
            <select class="form-control" name="employee" onkeypress="return noenter()">
                {{--if there is old input--}}
                @if(count(old('employee')) > 0)
                    @foreach($employees as $emp)
                        @if(old('employee') == $emp->user_id)
                            {{--mark the old input as selected--}}
                            <option value="{{$emp->user_id}}" selected>{{$emp->first_name}} {{$emp->last_name}}</option>
                        @else
                            {{--other values in list--}}
                            <option value="{{$emp->user_id}}">{{$emp->first_name}} {{$emp->last_name}}</option>
                        @endif
                    @endforeach

                @else

                    @foreach($employees as $emp)
                        <option value="{{$emp->user_id}}">{{$emp->first_name}} {{$emp->last_name}}</option>
                    @endforeach

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


