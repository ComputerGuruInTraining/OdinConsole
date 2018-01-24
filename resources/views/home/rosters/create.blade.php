@extends('layouts.master_layout_recent_date')
@include('sidebar')

@section('title-item')
    Add Shift
@stop

@section('custom-scripts')
    <script>

        //open contact tab upon page load and show the tab as active
        window.addEventListener("load", function () {

            //default tab to open
            openStep(event, 1);
            document.getElementById('loadPgTab').className += " active";

            //default show location & employee list
            showLocCheckboxes();
            showCheckboxes();

        }, false);

        //called when tab pressed or next/previous btn pressed
        function openStep(evt, stepNum) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");

            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");

            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(stepNum).style.display = "block";
            evt.currentTarget.className += " active";

            if (stepNum == 4) {
                checksInput();
            }
        }

        function createLocInputDiv(){
            //create an input text field for the number of checks for the location
            var locationInput = document.createElement("input");
            locationInput.setAttribute("name", "checks[]");
            locationInput.setAttribute("class", "form-control checksValue");
            locationInput.setAttribute("type", "text");
            locationInput.setAttribute("value", "");

            return locationInput;
        }

        function activeTab(stepNum){
            // Declare all variables
            var i, tablinks, active = stepNum - 1;

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");

            //return all tabs to inactive
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");

            }
                tablinks[active].className += " active";

        }

        function checksInput() {

            //ensure myArray will hold the values even upon page reload and tab nav
            checkAmt();

            var checksObj = [];
            var locationChecks = document.getElementById("locationChecks");
            var locCheckLbl =  document.getElementById("loc-checks-label");
            var x = 0;
            var locationInput = [];

            //create location input fields for the length of the myArray
            for(var y=0; y < myArray.length; y++) {

                locationInput[y] = createLocInputDiv();
            }

            //before clearing elements, get any values input by the user
            @if(count(old('checks')) > 1)

                @foreach (old('checks') as $oldCheck)

                    var jsOldChecks = "<?php echo $oldCheck;?>";

                    checksObj.push({
                        myArrayLocName: document.getElementById(myArray[x]).parentElement.innerHTML,
                        oldCheckValue: jsOldChecks
                    });

                    x++;

                @endforeach
            @else
                //have at least 1 value in the valueCheck
            //just loop through ordinary myArray without oldInput being added to object
            //conditions met: 2nd time naved to in the app, there is a value in the class tag, the input tag has been defined
//                if(myArray.length > 1) {
                    for (var v = 0; v < myArray.length; v++) {

                        var checksInputElem = document.getElementsByClassName("checksValue")[v];//error here? fixme

                        if (checksInputElem === undefined) {
                            checksObj.push({
                                myArrayLocName: document.getElementById(myArray[v]).parentElement.innerHTML
                            });
                            console.log("checksInputElem  equals undefined" + checksInputElem);

                        } else {
                            //yep, enters condition
                            console.log("checksInputElem  not equal to undefined" + checksInputElem);

                            checksObj.push({
                                myArrayLocName: document.getElementById(myArray[v]).parentElement.innerHTML,
                                valueChecks: checksInputElem.value
                            });
                        }
                    }
//                }else{
//
//
//                }
            @endif


            //before adding elements, clear all elements to ensure not added twice
            while (locationChecks.hasChildNodes()) {
                locationChecks.removeChild(locationChecks.firstChild);
            }

            if(myArray.length > 0) {
                //display the label and div for the children to be appended to
                locCheckLbl.style.display = "block";

                for (var i = 0; i < myArray.length; i++) {

                    var text = checksObj[i].myArrayLocName;

                    var subStringIndex = text.indexOf('>') + 1;//ie 1 character after the > character in the string
                    var locationName = text.substr(subStringIndex);

                    //create a div element for the location name
                    var locationDiv = document.createElement("div");

                    //add it to the parent div
                    locationChecks.appendChild(locationDiv);

                    //add text to the created div
                    locationDiv.innerHTML += locationName;

                    //add it to the parent div
                    locationChecks.appendChild(locationInput[i]);

                    //if only 1 location, default value of 1 in location checks input
                    if (myArray.length == 1) {

                        var tip = document.getElementById("checks-tip");
                        tip.style.display = "block";

                        locationInput[i].setAttribute("placeholder", 1);
                        locationInput[i].setAttribute("disabled", "disabled");

                    } else if (myArray.length > 1) {

                        //if more than 1 location, grab the value for location checks input from user input
                        @if(count(old('checks')) > 0)
                            locationInput[i].setAttribute("value", checksObj[i].oldCheckValue);
                        @else if (checksObj[i].valueChecks !== undefined)
                        //if there are values that the user has input
                            locationInput[i].setAttribute("value", checksObj[i].valueChecks);

                        @endif
                    }
                }
            }
        }
    </script>
@stop
@section('custom-styles')
    <style>
        /* Style the tab */
        body, .content-wrapper {
            min-height: 700px !important;

        }

        div.tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        /* Style the buttons inside the tab */
        div.tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        div.tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        div.tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent{
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }

        /*.alert-danger{*/
        /*border-color: #d73925;*/
        /*color: #fff !important;*/
        /*background-color: #990000 !important;*/
        /*}*/

    </style>
@stop

@section('custom-scripts-body')
    <script>

        var expanded = false;
        var locExpanded = false;
        var myArray;//myArray holds location ids

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

        //gather input from the select locations field
        function checkAmt() {

            myArray = [];

            $("#checkboxesLoc input:checkbox:checked").each(function () {
                myArray.push($(this).val());
                console.log(myArray);

            })


          /*  if (myArray.length > 1) {
                document.getElementById("checks_amt").removeAttribute("disabled");

            } else if (myArray.length == 1) {

                document.getElementById("checks_amt").setAttribute("disabled", "disabled");
            }*/
        }
    </script>
@stop

@section('page-content')

    <div class="tab">
        <button class="tablinks" onclick="openStep(event, '1')" id="loadPgTab">Date & Time</button>
        <button class="tablinks" onclick="openStep(event, '2')">Employees</button>
        <button class="tablinks" onclick="openStep(event, '3')">Location</button>
        <button class="tablinks" onclick="openStep(event, '4')">Location Checks</button>
    </div>

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

        <div id="1" class="tabcontent col-md-8">
                <div class='form-group'>
                    {!! Form::Label('title', 'Shift Title *') !!}
                    {{ Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'eg University Grounds Security',
                    'onkeypress'=>'return noenter()')) }}
                </div>

                <div class='form-group'>
                    {!! Form::Label('desc', 'Shift Description') !!}
                    {{ Form::text('desc', null, array('class' => 'form-control', 'placeholder' => 'eg Provide security services at the University of Texas at Austin', 'onkeypress'=>'return noenter()')) }}
                </div>

                <div class='form-group'>
                    {{ Form::label('startDate', 'Start Date *') }}
                    {{ Form::text('startDateTxt', '', array('class' => 'datepicker', 'onkeypress'=>'return noenter()', 'placeholder' => 'eg 03/20/2018')) }}
                    &nbsp;&nbsp;&nbsp;
                    {{ Form::label('startTime', 'Start Time *') }}
                    <input class="input-a" value="{{ old('startTime') }}" name="startTime" data-default="9:00"
                           placeholder="10:00">
                    @include('clock-picker')
                </div>

                <div class='form-group'>
                    {{ Form::label('endDate', 'End Date *&nbsp;&nbsp;') }}
                    {{ Form::text('endDateTxt', '', array('class' => 'datepicker',  'onkeypress'=>'return noenter()', 'placeholder' => 'eg 03/20/2018')) }}
                    &nbsp;&nbsp;&nbsp;
                    {{ Form::label('endTime', 'End Time *&nbsp;&nbsp;') }}
                    <input class="input-b" value="{{ old('endTime') }}" name="endTime" data-default="17:00"
                           onkeypress="return noenter()"
                           placeholder="16:00">
                    @include('clock-picker')
                </div>

                <div class='form-group form-buttons'>
                    <button onclick="openStep(event, 2); activeTab(2);" type="button" class="btn btn-primary">Next</button>
                    <a href="/rosters" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
                </div>
        </div>

        <div id="2" class="tabcontent col-md-8">

            <div class='form-group'>
                <div class="multiselect" width="100%">
                    <div class="selectBox">
                        <select>
                            <option class="select-option">Select Employee *</option>
                        </select>
                        <div class="overSelect"></div>
                    </div>

                    <div id="checkboxes">
                        @php
                            if(count(old('employees')) > 0){
                                    foreach(old('employees') as $empOldItem){
                                        foreach($empList as $emp){
                                            if($empOldItem == $emp->user_id)  {

                                                echo
                                                " <label for='".$empOldItem."'>
                                                <input type='checkbox' name='employees[]' value='".$empOldItem."' checked
                                                       id='".$empOldItem."'/>".$emp->first_name." ".$emp->last_name."</label>";

                                               break;
                                            }
                                        }
                                    }

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
                                                       id='".$emp->user_id."'/>".$emp->first_name." ".$emp->last_name."</label>";
                                   }

                             }
                            }else{
                            /*check if there are employees to display in the select list*/
                                if(count($empList) > 0){

                                     foreach($empList as $emp){
                                      echo
                                                " <label for='".$emp->user_id."'>
                                                <input type='checkbox' name='employees[]' value='".$emp->user_id."'
                                                       id='".$emp->user_id."'/>".$emp->first_name." ".$emp->last_name."</label>";
                                     }

                                 }else{
                                /*no employees to show so advise user to add locations*/
                                    echo
                                    " <label for='1'>Add items via Employees Feature</label>";

                                }
                            }
                        @endphp

                    </div>
                </div>
                <div class='form-group'>
                    <button onclick="openStep(event, 1); activeTab(1);" type="button" class="btn btn-info">Back</button>
                    <span class="form-buttons">
                        <button onclick="openStep(event, 3); activeTab(3);" type="button" class="btn btn-primary">Next</button>

                        <a href="/rosters" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
                    </span>
                </div>
            </div>
        </div>

        <div id="3" class="tabcontent col-md-8">

            <div class='form-group'>
                <div class="multiselect" width="100%">
                    <div class="selectBoxLoc">
                        <select>
                            <option class="select-option">Select Location *</option>
                        </select>
                        <div class="overSelect"></div>
                    </div>

                    <div id="checkboxesLoc" onchange="checkAmt()">
                        @php
                            if(count(old('locations')) > 0){
                                    foreach(old('locations') as $locOldItem){
                                        foreach($locList as $loc){
                                            if($locOldItem == $loc->id)  {

                                                echo
                                                " <label for='".$locOldItem."' class='locationNames'>
                                                <input type='checkbox' name='locations[]' value='".$locOldItem."'
                                                checked id='".$locOldItem."'/>".$loc->name."</label>";

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
                                                " <label for='".$loc->id."' class='locationNames'>
                                                <input type='checkbox' name='locations[]' value='".$loc->id."'
                                                       id='".$loc->id."'/>".$loc->name."</label>";
                                   }

                             }
                            }else{
                            /*check if there are locations to display*/
                                if(count($locList) > 0){
                                     foreach($locList as $loc){
                                      echo
                                                " <label for='".$loc->id."' class='locationNames'>
                                                <input type='checkbox' name='locations[]' value='".$loc->id."'
                                                       id='".$loc->id."'/>".$loc->name."</label>";
                                     }
                                }else{
                                /*no locations to show so advise user to add locations*/
                                    echo
                                    " <label for='1'>Add items via Locations Feature</label>";

                                }
                            }
                        @endphp
                    </div>
                </div>

                <div class='form-group'>
                    <button onclick="openStep(event, 2); activeTab(2);" type="button" class="btn btn-info">Back</button>
                    <span class="form-buttons">
                        <button onclick="openStep(event, 4); activeTab(4);" type="button" class="btn btn-primary">Next</button>
                        <a href="/rosters" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
                    </span>
                </div>

            </div>
        </div>
        <div id="4" class="tabcontent col-md-8">

            <div class="alert alert-danger alert-custom">
                <strong>Important:</strong> Location Checks will show on the mobile as progress flags and employees will be
                required to check the location the nominated number of times in order to complete the shift requirements.
            </div>

            <div class="alert alert-warning alert-custom" id="checks-tip" style="display:none;">
                <strong>Tip:</strong> As only 1 location is selected, Location Checks is disabled and the default value of 1 will be applied.
            </div>

            <div class='form-group'>
                {!! Form::Label('checks', 'Location Checks *', array('id' => 'loc-checks-label', 'style' => 'display:none;')) !!}

                <div id="locationChecks"></div>

                <div>
                   <button onclick="openStep(event, 3); activeTab(3);" type="button" class="btn btn-info">Back</button>
                    <span class="form-buttons">
                       {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
                       <a href="/rosters" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
                    </span>
                </div>
            </div>
        </div>

{{ Form::close() }}

@stop

