@extends('layouts.master_layout_recent_date')
@include('sidebar')

@section('title-item')
    Add Shift
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

            //fill the locations array with the locations selected by user
//            checkAmt();
            //if checksObj has not already been defined, else
            @if (count($errors) > 0)
            //we have reentered page due to an input error, so check for oldInput for location checks field
                oldInput();
            @endif

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
//            locationInput.setAttribute("style", "")

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
//                console.log("checksObj undefined");
                //first call is when checkAmt() is called and checksObj is not defined, so intialise and assign values
                initialiseEmptyChecksObject();
            }else{
//                console.log("checksObj is defined");

//                if(checksObj[0].myArrayLocName === undefined){
//                    initialAssignChecksObject();
//                }else{

                    reinitialiseChecksObject();
//                }

            }
//            console.log(checksObj);

        }

        function initialiseEmptyChecksObject(){

            //initiliase upon page load,
            //then when myArray has new values (ie changes to selected locations)
            //we need to reassign checksObject with the number of lcoations and assign the values/old input we had to the new objects



            //order needs to be




            //first checksObj initialise & assign values from myArray
            if(checksObj === undefined) {
                checksObj = [];
//                console.log(myArray.length);

                //initialise values in checksObj for each array item (ie location)
                for (var a = 0; a < myArray.length; a++) {
                    checksObj.push({
                        myArrayLocName: null,
                        oldCheckValue: 0,
                        valueChecks: 0,//initialise value here for assignment later when current input values checked
                    });
                }

                initialAssignChecksObject();//todo: change to not a loop in this function too



            }
//            console.log(checksObj);

        }

        /*
         * initialise empty every page load
         * initialise myArray
         * check current checksObject to see if location item already in there,
         * if so keep, (values in there still)
         * if not, delete
         * for all new location items, add
         * */
        //note: checksObj values
        //reorders the checksObj
        function reinitialiseChecksObject() {


            //check that checksObj has values in it
            if (checksObj !== undefined) {
                console.log("checksObj length" + checksObj.length);


                var originalObjectLength = checksObj.length;
                var newObjectLength = checksObj.length;

                /*Delete objects not selected this time by user*/
                //compare each location array item to each array objects to see which items are not in array objects
                // and need to be removed from array objects
                console.log("checksObj " + checksObj, "myArray " + myArray);

                for (var object1 = 0; object1 < originalObjectLength; object1++) {

                    var deleteObject = true;

                    for (var array1 = 0; array1 < myArray.length; array1++) {
                        var myArrayElem = document.getElementById(myArray[array1]).parentElement.innerHTML;//uses the element id to get the location text
                        //compare the location name in checksObj to the document element location name for myArray

                        //delete
                        if (checksObj[object1].myArrayLocName == myArrayElem) {

                            console.log("equal delete object 1 then myArrayElem " + checksObj[object1].myArrayLocName,  myArrayElem)

                            deleteObject = false;
                        }
                    }

                    //!= means stillSelected = false, which means the checks objects are not equal to the array item, so splice the checks object
                    if (deleteObject == true) {
                        console.log("spliced object " + object1);
                        //needs to be deleted
                        checksObj.splice(object1, 1);
//                        deleteObject = true;
                    }
                    console.log("checksObj in object for loop" + checksObj);




                }
                console.log("checksObj outside both for loops" + checksObj);

                /*Add objects selected this time by user, but not previous times*/
                //compare each location array item to each array objects to see which items are not in array objects
                // and need to be added to array objects
                for (var array2 = 0; array2 < myArray.length; array2++) {

                    var addObject = true;

                    var myArrayElement = document.getElementById(myArray[array2]).parentElement.innerHTML;//uses the element id to get the location text
                    //compare the location name in checksObj to the document element location name for myArray
                    for (var object2 = 0; object2 < newObjectLength; object2++) {

                        if (checksObj[object2].myArrayLocName == myArrayElement) {
                            console.log("equal add object 2 then myArrayElem " + checksObj[object2].myArrayLocName,  myArrayElement)

                            addObject = false;
                        }
                    }

                    if (addObject == true) {

                        checksObj.push({
                            myArrayLocName: myArrayElement,
                            oldCheckValue: 0,
                            valueChecks: 0,//initialise value here for assignment later when current input values checked
                        });

                        //increase the length of the object just to be sure we compare every object to the locations array,
                        // else errors might result
                        newObjectLength++;
//                        addObject = true;

                    }
                }
            }
        }

//presuming if 4 fields, 4 old inputs
        function oldInput(){
            checkAmt();

            initialiseOrReinitialiseChecksObj();
            console.log("old input entered", checksObj[0]);

//            //fill the locations array with the locations selected by user
//            checkAmt();

            //we have some old input
            //if we only have 2 of the inputs
//            if(myArray !== undefined) {

                var x = 0;

                @if(count(old('checks')) > 0)

                    @foreach (old('checks') as $oldCheck)

                        var jsOldChecks = "<?php echo $oldCheck;?>";

                        //fixme: check location name first before appending the div with the value
                        //            checks object will only be as longa s the input ie if 2/5 had a value only 2 objects

                        //may need to assign the value, we'll see FIxme
                        if (checksObj[x].myArrayLocName = document.getElementById(myArray[x]).parentElement.innerHTML) {
                            checksObj[x].oldCheckValue = jsOldChecks;
                        }

                        x++;

                        console.log(checksObj, jsOldChecks);

                    @endforeach

                @endif
//            }
        }

        function initialAssignChecksObject() {
            for (var index = 0; index < myArray.length; index++) {

                checksObj[index].myArrayLocName = document.getElementById(myArray[index]).parentElement.innerHTML;

            }
        }

        //before change the myArray, need to get values and add to them to the object
        function checksValues(){
            /*values*/
            if((myArray !== undefined)&&(checksObj !== undefined)) {

                for (var v = 0; v < myArray.length; v++) {

                    /*Check input values by user (not including old input)*/
                    var checksInputElem = document.getElementsByClassName("checksValue")[v];//error here? fixme

//                    checksObj[v].myArrayLocName = document.getElementById(myArray[v]).parentElement.innerHTML;


                    //no values input by user
                    if (checksInputElem !== undefined) {

                        //we need to check the

                        //first we check for input already
                        //then

//             checksObj[v].myArrayLocName = document.getElementById(myArray[v]).parentElement.innerHTML

//             console.log("checksInputElem  equals undefined" + checksInputElem);
//
//             } else {
                        //yep there are values, enters condition
//                        console.log("checksInputElem  is defined" + checksInputElem);

                        //                            checksObj.push({
                        checksObj[v].valueChecks = checksInputElem.value;
                        //
                        //
//                        console.log("checks values" + checksObj[v].valueChecks);
                    }

                }
            }
        }

        //for each location selected, create a div for the location checks input with a location name label
        //called every time the locations tab is navigated to
        function checksInput() {

            //ensure myArray with the locations selected will hold the values even upon page reload and tab nav
//            checksValues();
            checkAmt();
            initialiseOrReinitialiseChecksObj();


//            checksObj = [];
//            initialiseChecksObject();
            var locationChecks = document.getElementById("locationChecks");
            var locCheckLbl =  document.getElementById("loc-checks-label");
            var locationInput = [];

//            locationChecks.style.display = "inline-block";

            //create location input fields for the length of the myArray
            for(var y=0; y < myArray.length; y++) {

                locationInput[y] = createLocInputDiv();
            }

            //before clearing elements, get any values input by the user
            //if there is old input in the case of Create btn being tapped but form not filled out correctly

            //else there is no old input

            //if there are values in the checksInputElem


            //scenario 1: values, old input, then no change
            //scenario 2: old input, then values as well or replacing
            //scenario 3: values, and then no old input

            //plan = upon page load, check myArray & check oldInput, if so create an object with oldChecks and valueChecks

            //later, when check input values & selected locations (upon every nav to), if there is oldChecks and valueChecks,
//            if therefore
//assign values to object
            //if there is a value, then use valueChecks (initialised upon page load) , if not, use oldChecks if there is one

                //have at least 1 value in the valueCheck
            //just loop through ordinary myArray without oldInput being added to object
            //conditions met: 2nd time naved to in the app, there is a value in the class tag, the input tag has been defined
//                if(myArray.length > 1) {


//assign the values
            //the first time we need to assign myArray to all the checksObj initialised objects
            //the subsequent times we need to check if the object is already in there before assigning an object
            //same as we do when reinitialising,
            //so just keep in reinitialise
            //but also, for the first time
            //we do initialAssign, first page load, first tab press:::




            //when we press the tab the second time, we need to reinitialise the correct amount and the item will already be in the array
            //and we just keep it.





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




                    }

                    //todo: consider default of 1
                    if(checksObj[i].valueChecks != 0){

                        locationInput[i].setAttribute("value", checksObj[i].valueChecks);
                    }else {
                        //no value from current inputs, check if we have oldInput
                        if(checksObj[i].oldCheckValue != 0){
                            locationInput[i].setAttribute("value", checksObj[i].oldCheckValue);


                        }
                    }



                }
            }else{
                //hide the location checks label
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

            //before updating the value of myArray, check for current input values and old input and assign to checksObj
            checksValues();
//            oldInput();


            myArray = [];

            $("#checkboxesLoc input:checkbox:checked").each(function () {
                myArray.push($(this).val());
//                console.log("checkAmt()" + myArray);

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

