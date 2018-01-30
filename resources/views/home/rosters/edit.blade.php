@extends('layouts.master_layout_recent_date')
@include('sidebar')

@section('title-item')
    Edit Shift
@stop

@section('custom-scripts')
    <script>

        var checksObj;

        //open contact tab upon page load and show the tab as active
        window.addEventListener("load", function () {

            //default tab to open
            openStep(event, 1);
            document.getElementById('loadPgTab').className += " active";

            //default show location & employee list
            showLocCheckboxes();
            showCheckboxes();

            //if checksObj has not already been defined, else
            @if (count($errors) > 0)
            //we have reentered page due to an input error, so check for oldInput for location checks field
                oldInput();
            @endif

            originalValuesForEdit();

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

        //if checksObj is not defined, initialise object the entire length of myArray
        //else if it has already been defined, shorten the array or lengthen based upon whether objects selected still in location list
        function initialiseOrReinitialiseChecksObj(){

            if(checksObj === undefined) {
                //first call is when checkAmt() is called and checksObj is not defined, so intialise and assign values
                initialiseEmptyChecksObject();
            }else{
                reinitialiseChecksObject();
            }
        }

        function initialiseEmptyChecksObject(){

            //first checksObj initialise & assign values from myArray
            if(checksObj === undefined) {
                checksObj = [];

                //initialise values in checksObj for each array item (ie location)
                for (var a = 0; a < myArray.length; a++) {
                    checksObj.push({
                        myArrayLocName: null,
                        myArrayLocId: 0,
                        oldCheckValue: 0,
                        valueChecks: 0,
                        valueForEdit: 0//initialise value here for assignment later when current input values checked
                    });
                }

                initialAssignChecksObject();
            }
        }

        //note: checksObj values
        //reorders the checksObj
        function reinitialiseChecksObject() {


            //check that checksObj has values in it
            if (checksObj !== undefined) {
                console.log("checksObj length" + checksObj.length);

                /*Before deleting and adding objects, just find out and store in an array the items to be deleted and added*/
                /*objects to be deleted ie not selected this time by user*/
                //compare each location array item to each array objects to see which items are not in array objects
                // and need to be removed from array objects
                var indexDelete = [];
                var arrayIndexAdd = [];

                for (var object1 = 0; object1 < checksObj.length; object1++) {

                    var deleteObject = true;

                    for (var array1 = 0; array1 < myArray.length; array1++) {
                        var myArrayElem = document.getElementById(myArray[array1]).parentElement.innerHTML;//uses the element id to get the location text

                        //compare the location name in checksObj to the document element location name for myArray

                        if (checksObj[object1].myArrayLocName == myArrayElem) {

                            deleteObject = false;
                        }
                    }

                    if (deleteObject == true) {
                        indexDelete.push(checksObj[object1]);
                    }
                }

                /*to be added to objects ie the array items selected this time by user, but not previous times*/
                //compare each location array item to each array objects to see which items are not in array objects
                // and need to be added to array objects
                for (var array2 = 0; array2 < myArray.length; array2++) {

                    var addObject = true;

                    var myArrayElement = document.getElementById(myArray[array2]).parentElement.innerHTML;//uses the element id to get the location text

                    //compare the location name in checksObj to the document element location name for myArray
                    for (var object2 = 0; object2 < checksObj.length; object2++) {

                        if (checksObj[object2].myArrayLocName == myArrayElement) {

                            addObject = false;
                        }
                    }

                    if (addObject == true) {

                        arrayIndexAdd.push(myArray[array2]);
                    }
                }

                var checksLength = checksObj.length;

                //delete from object those objects not selected in array this selection
                for(var k= 0; k < indexDelete.length; k++){

                    //need to check the indexDelete object against the checksObj to ensure the correct object
                    for(var d= 0; d < checksLength; d++) {
                        if(checksObj[d] !== undefined){
                            if(checksObj[d].myArrayLocId == indexDelete[k].myArrayLocId){

                                checksObj.splice(d, 1);
                            }
                        }
                    }
                }

                //add array items to object
                for(var a = 0; a < arrayIndexAdd.length; a++) {

                    var myArrayAdd = document.getElementById(arrayIndexAdd[a]).parentElement.innerHTML;//uses the element id to get the location text

                    checksObj.push({
                        myArrayLocName: myArrayAdd,
                        myArrayLocId: arrayIndexAdd[a],
                        oldCheckValue: 0,
                        valueChecks: 0,
                        valueForEdit: 0
                    });
                }
            }
        }

        function originalValuesForEdit(){
            checkAmt();

            initialiseOrReinitialiseChecksObj();

//            var c = 0;

            @if(count($myLocations) > 1)

                @foreach($assigned as $shiftEdit)

                    var valueForEdit = "<?php echo $shiftEdit->checks;?>";
                    var valueForEditLocation = "<?php echo $shiftEdit->location;?>";

                        for (var b = 0; b < checksObj.length; b++) {
                            //convert checksObject myArrayLocName to just the location name for comparison
                            var text = checksObj[b].myArrayLocName;

                            var subStringIndex = text.indexOf('>') + 1;//ie 1 character after the > character in the string
                            var locationName = text.substr(subStringIndex);

                            if (locationName.trim() == valueForEditLocation) {

                                checksObj[b].valueForEdit = valueForEdit;
                                break;
                            }
                        }
                @endforeach
            @endif
        }

        function oldInput(){
            checkAmt();

            initialiseOrReinitialiseChecksObj();

            var x = 0;

            @if(count(old('checks')) > 0)

                @foreach (old('checks') as $oldCheck)

                    var jsOldChecks = "<?php echo $oldCheck;?>";

                    //just checks that the object for this index is equal to the array for this index
                    if (checksObj[x].myArrayLocName = document.getElementById(myArray[x]).parentElement.innerHTML) {

                        checksObj[x].oldCheckValue = jsOldChecks;
                    }

                    x++;

                @endforeach
            @endif
        }

        function initialAssignChecksObject() {
            for (var index = 0; index < myArray.length; index++) {

                checksObj[index].myArrayLocName = document.getElementById(myArray[index]).parentElement.innerHTML;
                checksObj[index].myArrayLocId = myArray[index];
            }
        }

        //before change the myArray, need to get values and add to them to the object
        function checksValues(){
            /*values*/
            if((myArray !== undefined)&&(checksObj !== undefined)) {

                for (var v = 0; v < myArray.length; v++) {

                    /*Check input values by user (not including old input)*/
                    var checksInputElem = document.getElementsByClassName("checksValue")[v];

                    //no values input by user
                    if (checksInputElem !== undefined) {

                        checksObj[v].valueChecks = checksInputElem.value;
                    }
                }
            }
        }

        //for each location selected, create a div for the location checks input with a location name label
        //called every time the locations tab is navigated to
        function checksInput() {

            checkAmt();
            initialiseOrReinitialiseChecksObj();

            var locationChecks = document.getElementById("locationChecks");
            var locCheckLbl =  document.getElementById("loc-checks-label");
            var locationInput = [];

            //create location input fields for the length of the myArray
            for(var y=0; y < myArray.length; y++) {

                locationInput[y] = createLocInputDiv();
            }

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

                    }

                    //todo: consider default of 1
                    if(checksObj[i].valueChecks != 0){

                        locationInput[i].setAttribute("value", checksObj[i].valueChecks);
                    }else{
                        //no current user input for the object, so check if old input or use the value coming from the saved item in the db (ie valueForEdit)
                        if(checksObj[i].oldCheckValue != 0){
                            locationInput[i].setAttribute("value", checksObj[i].oldCheckValue);
                        }else{
                            locationInput[i].setAttribute("value", checksObj[i].valueForEdit);

                        }


                    }
                }

            }else{
                //hide the location checks label as myArray length is 0 and therefore no locations selected
                locCheckLbl.style.display = "none";
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

    </style>
@stop

@section('custom-scripts-body')
    <script>

        var expanded = false;
        var locExpanded = false;
        var myArray;

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

            //before updating the value of myArray, check for current input values and assign to checksObj
            checksValues();

            myArray = [];

            $("#checkboxesLoc input:checkbox:checked").each(function () {
                myArray.push($(this).val());
            })

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

    {{ Form::open(['route' => ['rosters.update', $assigned[0]->assigned_shift_id], 'method'=>'put']) }}

        <div id="1" class="tabcontent col-md-8">
                <div class='form-group'>
                    {!! Form::Label('title', 'Shift Title *') !!}
                    {{ Form::text('title', $assigned[0]->shift_title, array('class' => 'form-control',
                        'placeholder' => 'eg University Grounds Security', 'onkeypress'=>'return noenter()')) }}
                </div>

                <div class='form-group'>
                    {!! Form::Label('desc', 'Shift Description') !!}
                    {{ Form::text('desc', $assigned[0]->shift_description, array('class' => 'form-control', 'placeholder' => 'eg Provide security services at the University of Texas at Austin', 'onkeypress'=>'return noenter()')) }}
                </div>

                <div class='form-group'>
                    {{ Form::label('startDate', 'Start Date *') }}
                    {{ Form::text('startDateTxt', $startDate, array('class' => 'datepicker')) }}
                    &nbsp;&nbsp;&nbsp;
                    {{ Form::label('startTime', 'Start Time *') }}
                    <input class="input-a" value="{{ old('startTime') ? old('startTime') : $startTime}}" name="startTime"
                           data-default={{$startTime}} placeholder={{$startTime}}>
                    @include('clock-picker')
                </div>

                <div class='form-group'>
                    {{ Form::label('endDate', 'End Date *&nbsp;&nbsp;') }}
                    {{ Form::text('endDateTxt', $endDate, array('class' => 'datepicker')) }}
                    &nbsp;&nbsp;&nbsp;
                    {{ Form::label('endTime', 'End Time *&nbsp;&nbsp;') }}
                    <input class="input-b" value={{ old('endTime') ? old('endTime') : $endTime}} name="endTime"
                           data-default={{$endTime}} placeholder={{$endTime}}>
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
                                        foreach($employeesAll as $emp){
                                            if($empOldItem == $emp->user_id)  {

                                                echo
                                                " <label for='".$empOldItem."'>
                                                <input type='checkbox' name='employees[]' value='".$empOldItem."' checked
                                                       id='".$empOldItem."'/>".$emp->first_name." ".$emp->last_name."</label>";

                                               break;
                                            }
                                        }
                                    }

                               foreach($employeesAll as $emp){

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
                                     //if no old input, use values stored in db and other items not stored in db

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
                                        foreach($locationsAll as $loc){
                                            if($locOldItem == $loc->id)  {

                                                echo
                                                " <label for='".$locOldItem."' class='locationNames'>
                                                <input type='checkbox' name='locations[]' value='".$locOldItem."' checked
                                                       id='".$locOldItem."'/>".$loc->name."</label>";

                                               break;
                                            }
                                        }
                                    }

                               foreach($locationsAll as $loc){

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
                             //if no old input, use values stored in db and other items not stored in db
                                }else{
                                //Highlight assigned_shift_locations
                                    foreach($myLocations as $myLocation){
                                        echo "<label for='".$myLocation->location_id."' class='locationNames'>
                                        <input type='checkbox' name='locations[]' value='".$myLocation->location_id."'
                                               checked
                                               id='".$myLocation->location_id."'/>    ".$myLocation->location."</label>";
                                    }

                                    //List all the locations besides those that are selected at the top of the list
                                    //because stored in db
                                    foreach($locList as $loc){
                                     echo "<label for='".$loc->id."' class='locationNames'>
                                        <input type='checkbox' name='locations[]' value='".$loc->id."'
                                               id='".$loc->id."'/>    ".$loc->name."</label>";


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
                <strong>Important:</strong> Location Checks will show on the mobile as progress flags. Employees will be
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

