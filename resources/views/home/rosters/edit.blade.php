@extends('layouts.master_layout_recent_date')
@extends('sidebar')

@section('title-item')
    Edit Shift
@stop

@section('custom-scripts-body')
    <script>

        var expanded = false;
        var locExpanded = false;

        function showCheckboxesEdit() {
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

        function showLocCheckboxesEdit() {
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

        function checkAmtEdit() {

            myArray = [];

            $("#checkboxesLoc input:checkbox:checked").each(function () {
                myArray.push($(this).val());
            })
            var elem = document.getElementById("checks_amt");

            if (myArray.length > 1) {
                if (elem.hasAttribute('disabled')) {
                    elem.removeAttribute("disabled");
                }
            } else if (myArray.length == 1) {
                if (!elem.hasAttribute('disabled')) {
                    elem.setAttribute("disabled", "disabled");
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
                <input class="input-a" value="{{ old('startTime') ? old('startTime') : $startTime}}" name="startTime"
                       data-default={{$startTime}} placeholder={{$startTime}}>
                @include('clock-picker')
            </div>

            <div class='form-group'>
                {{ Form::label('endDate', 'End Date&nbsp;&nbsp;') }}
                {{ Form::text('endDateTxt', $endDate, array('class' => 'datepicker')) }}
                &nbsp;&nbsp;&nbsp;
                {{ Form::label('endTime', 'End Time&nbsp;&nbsp;') }}
                <input class="input-b" value={{ old('endTime') ? old('endTime') : $endTime}} name="endTime"
                       data-default={{$endTime}} placeholder={{$endTime}}>
                @include('clock-picker')
            </div>

            <div class='form-group'>
                <div class="multiselect" width="100%">
                    <div class="selectBoxLoc" onclick="showLocCheckboxesEdit()">
                        <select>
                            <option class="select-option">Select Location:</option>
                        </select>
                        <div class="overSelect"></div>
                    </div>

                    <div id="checkboxesLoc" onchange="checkAmtEdit()">
                        @php
                            if(count(old('locations')) > 0){
                                    foreach(old('locations') as $locOldItem){
                                        foreach($locList as $loc){
                                            if($locOldItem == $loc->id)  {

                                                echo
                                                " <label for='".$locOldItem."'>
                                                <input type='checkbox' name='locations[]' value='".$locOldItem."' checked
                                                       id='".$locOldItem."'/>".$loc->name."</label>";

                                               break;
                                            }
                                        }
                                    }

                               foreach($locList as $loc){

                                   $sameSame = false;

                                   foreach(old('locations') as $locOldItem){
                                        if($loc->id == $locOldItem){
                                                $sameSame = true;
                                        }

                                    }

                                    if(!$sameSame){
                                        echo
                                                " <label for='".$loc->id."'>
                                                <input type='checkbox' name='locations[]' value='".$loc->id."'
                                                       id='".$loc->id."'/>".$loc->name."</label>";
                                   }
                                }

                               /*Ensure the values that were stored in the database display in the list
                                if not included in old input*/
                                foreach($myLocations as $myLocation){

                                       $notEqualLoc = false;

                                       foreach(old('locations') as $locOldItem){
                                            if($myLocation->location_id != $locOldItem){
                                                $notEqualLoc = true;
                                            }
                                       }

                                      if($notEqualLoc){
                                            echo "<label for='".$myLocation->location_id."'>
                                            <input type='checkbox' name='employees[]' value='".$myLocation->location_id."'
                                           id='".$myLocation->location_id."'/>    ".$myLocation->location."</label>";
                                       }
                               }
                            }else{
                            //Highlight assigned_shift_locations
                                foreach($myLocations as $myLocation){
                                    echo "<label for='".$myLocation->location_id."'>
                                    <input type='checkbox' name='locations[]' value='".$myLocation->location_id."'
                                           checked
                                           id='".$myLocation->location_id."'/>    ".$myLocation->location."</label>";
                                }

                                //List all the locations besides those that are selected at the top of the list
                                //because stored in db
                                foreach($locList as $loc){
                                 echo "<label for='".$loc->id."'>
                                    <input type='checkbox' name='locations[]' value='".$loc->id."'
                                           id='".$loc->id."'/>    ".$loc->name."</label>";


                                }
                            }
                        @endphp

                        {{--Highlight assigned shift locations--}}
                        {{--@foreach($myLocations as $myLocation)--}}
                            {{--<label for="{{$myLocation->location_id}}">--}}
                                {{--<input type="checkbox" name="locations[]" value="{{$myLocation->location_id}}" checked--}}
                                       {{--id="{{$myLocation->location_id}}"/> {{$myLocation->location}}</label>--}}
                        {{--@endforeach--}}

                        {{--List all the locations besides those that are selected at the top of the list because stored in db--}}
                        {{--@foreach($locList as $loc)--}}
                            {{--<label for="{{$loc->id}}">--}}
                                {{--<input type="checkbox" name="locations[]" value="{{$loc->id}}"--}}
                                       {{--id="{{$loc->id}}"/> {{$loc->name}}</label>--}}
                        {{--@endforeach--}}

                    </div>

                </div>
            </div>

            <div class='form-group'>

                <div class="multiselect" width="100%">
                    <div class="selectBox" onclick="showCheckboxesEdit()">
                        <select>
                            <option class="select-option">Select Employee:</option>
                        </select>
                        <div class="overSelect"></div>
                    </div>

                    <div id="checkboxes">
                        @php
                            if(count(old('employees')) > 0){

                                    //display oldInput as checked checkboxes
                                    foreach(old('employees') as $empOldItem){
                                        //display the items that were not already stored in the db and are old input
                                        foreach($empList as $emp){
                                            if($empOldItem == $emp->user_id)  {

                                                echo
                                                " <label for='".$empOldItem."'>
                                                <input type='checkbox' name='employees[]' value='".$empOldItem."'
                                                        checked
                                                       id='".$empOldItem."'/>   ".$emp->first_name." ".$emp->last_name."</label>";

                                               break;
                                            }
                                        }
                                        //display the items that were stored in the db and are old input
                                        foreach($myEmployees as $myEmployee){
                                            if($empOldItem == $myEmployee->mobile_user_id)  {
                                                echo "<label for='".$myEmployee->mobile_user_id."'>
                                                <input type='checkbox' name='employees[]' value='".$myEmployee->mobile_user_id."'
                                                       checked
                                                       id='".$myEmployee->mobile_user_id."'/>    ".$myEmployee->employee."</label>";
                                           }
                                        }
                                    }

                                    //display the rest of the employees not stored in the db previously as unchecked checkboxes
                                   foreach($empList as $emp){

                                       $sameEmp = false;

                                       foreach(old('employees') as $empOldItem){
                                            if($emp->user_id == $empOldItem){
                                                    $sameEmp = true;
                                            }

                                        }

                                        if(!$sameEmp){
                                            echo
                                                    " <label for='".$emp->user_id."'>
                                                    <input type='checkbox' name='employees[]' value='".$emp->user_id."'
                                                           id='".$emp->user_id."'/>    ".$emp->first_name." ".$emp->last_name."</label>";
                                        }
                                    }

                                  /*display the rest of the employees that were stored in the db previously*/
                                   foreach($myEmployees as $myEmployee){

                                       $notEqual = false;

                                       foreach(old('employees') as $empOldItem){
                                            if($myEmployee->mobile_user_id != old($empOldItem)){
                                                $notEqual = true;
                                            }
                                       }

                                      if($notEqual){
                                            echo "<label for='".$myEmployee->mobile_user_id."'>
                                            <input type='checkbox' name='employees[]' value='".$myEmployee->mobile_user_id."'
                                           id='".$myEmployee->mobile_user_id."'/>    ".$myEmployee->employee."</label>";
                                       }
                                   }

                            }else{

                                 //Highlight assigned_shift_employees
                                foreach($myEmployees as $myEmployee){
                                    echo "<label for='".$myEmployee->mobile_user_id."'>
                                    <input type='checkbox' name='employees[]' value='".$myEmployee->mobile_user_id."'
                                           checked
                                           id='".$myEmployee->mobile_user_id."'/>    ".$myEmployee->employee."</label>";
                                }

                                //List all the employees besides those that are selected at the top of the list because stored in db
                                foreach($empList as $emp){
                                 echo "<label for='".$emp->user_id."'>
                                    <input type='checkbox' name='employees[]' value='".$emp->user_id."'
                                           id='".$emp->user_id."'/>    ".$emp->first_name." ".$emp->last_name."</label>";


                                }
                            }
                        @endphp
                    </div>
                </div>

            </div>

            <div class="alert alert-warning alert-custom">
                <strong>Important!</strong> Location Checks will default to 1 if only 1 location is selected
            </div>

            <div class='form-group'>
                {!! Form::Label('checks', 'Location Checks:') !!}
                {{--apply disabled to the checks field if only 1 location has been stored in the db--}}
                @if(count($myLocations) > 1)
                    {{ Form::text('checks', $assigned[0]->checks, array('class' => 'form-control',
                    'id' => 'checks_amt')) }}

                @elseif(count($myLocations) == 1)
                    {{ Form::text('checks', 1, array('class' => 'form-control',
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