@extends('layouts.master_layout_recent_date')
@include('sidebar')

@section('title-item')
    Add Shift
@stop

@section('custom-scripts-body')
    <script>

        var expanded = false;
        var locExpanded = false;

        function showCheckboxes() {
            var checkboxes = document.getElementById("checkboxes");
            var selectBox = document.getElementsByClassName("selectBox")[0];
            if (!expanded) {
                checkboxes.style.display = "block";
                selectBox.style.margin = "0px";

                expanded = true;
            } else {
                checkboxes.style.display = "none";
                selectBox.style.margin = "15px";

                expanded = false;
            }
        }

        function showLocCheckboxes() {
            var checkboxesLoc = document.getElementById("checkboxesLoc");
            var selectBoxLoc = document.getElementsByClassName("selectBoxLoc")[0];

            if (!locExpanded) {
                checkboxesLoc.style.display = "block";
                selectBoxLoc.style.margin = "0px";

                locExpanded = true;
            } else {
                checkboxesLoc.style.display = "none";
                selectBoxLoc.style.margin = "15px";

                locExpanded = false;
            }
        }

        function checkAmt() {

            myArray = [];

            $("#checkboxesLoc input:checkbox:checked").each(function () {
                myArray.push($(this).val());
            })

            if (myArray.length > 1) {
                document.getElementById("checks_amt").removeAttribute("disabled");
            } else if (myArray.length == 1) {
                document.getElementById("checks_amt").setAttribute("disabled", "disabled");
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

    {{ Form::open(['role' => 'form', 'url' => '/rosters']) }}
    <div class='form-pages col-md-8'>
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
            {{ Form::text('startDateTxt', '', array('class' => 'datepicker', 'onkeypress'=>'return noenter()', 'placeholder' => 'eg 03/20/2018')) }}
            &nbsp;&nbsp;&nbsp;
            {{ Form::label('startTime', 'Start Time') }}
            <input class="input-a" value="" name="startTime" data-default="9:00" placeholder="10:00">
            @include('clock-picker')
        </div>

        <div class='form-group'>
            {{ Form::label('endDate', 'End Date&nbsp;&nbsp;') }}
            {{ Form::text('endDateTxt', '', array('class' => 'datepicker',  'onkeypress'=>'return noenter()', 'placeholder' => 'eg 03/20/2018')) }}
            &nbsp;&nbsp;&nbsp;
            {{ Form::label('endTime', 'End Time&nbsp;&nbsp;') }}
            <input class="input-b" value="" name="endTime" data-default="17:00" onkeypress="return noenter()"
                   placeholder="16:00">
            @include('clock-picker')
        </div>

        <div class='form-group'>
            <div class="multiselect" width="100%">
                <div class="selectBoxLoc" onclick="showLocCheckboxes()">
                    <select>
                        <option class="select-option">Select Location:</option>
                    </select>
                    <div class="overSelect"></div>
                </div>

                <div id="checkboxesLoc" onchange="checkAmt()">
                    @if(isset($locOld))
                        @if(count($locOld) > 1)
                            @foreach($locOld as $locOldItem)
                                {{$locOldItem}}
                            @endforeach
                        @else
                            $locOld
                        @endif
                    @else
                        @foreach($locList as $loc)

                            <label for="{{$loc->id}}">
                                <input type="checkbox" name="locations[]" value="{{$loc->id}}"
                                       id="{{$loc->id}}"/> {{$loc->name}}</label>
                        @endforeach
                    @endif

                </div>

            </div>
        </div>

        <div class='form-group'>

            <div class="multiselect" width="100%">
                <div class="selectBox" onclick="showCheckboxes()">
                    <select>
                        <option class="select-option">Select Employee:</option>
                    </select>
                    <div class="overSelect"></div>
                </div>

                <div id="checkboxes">

                    @foreach($empList as $emp)
                        <label for="{{$emp->user_id}}">
                            <input type="checkbox" name="employees[]" value="{{$emp->user_id}}"
                                   id="{{$emp->user_id}}"/> {{$emp->first_name}} {{$emp->last_name}}</label>
                    @endforeach

                </div>

            </div>

        </div>

        <div class="alert alert-warning alert-custom">
            <strong>Important!</strong> Location Checks will default to 1 if only 1 location is selected
        </div>

        <div class='form-group'>
            {!! Form::Label('checks', 'Location Checks:') !!}
            {{ Form::text('checks', 1, array('class' => 'form-control', 'id' => 'checks_amt', 'disabled' => 'disabled')) }}
        </div>

        <div class='form-group form-buttons'>
            {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
            <a href="/rosters" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        </div>

        {{ Form::close() }}

    </div>
@stop