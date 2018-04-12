@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item') Settings @stop

@section('custom-scripts')
    <script>

        //open contact tab upon page load and show the tab as active
        window.addEventListener("load", function(){

            openSetting(event, 'Users');

            document.getElementById('loadPgTab').className += " active";

            //default display
            var errorPrimary = document.getElementById('error-primary');
            errorPrimary.style.display = "none";

        }, false);

        function openSetting(evt, name) {
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
            document.getElementById(name).style.display = "block";
            evt.currentTarget.className += " active";
        }

        function enableEditPrimary(){

            var inputArray = document.getElementsByClassName("primaryInput");//one for each user
            console.log(inputArray);

            for (i = 0; i < inputArray.length; i++){

                inputArray[i].removeAttribute('disabled');
            }
        }

        function checkSelectedIsCurrent(selectedValue){

            var sessionId = "<?php echo session('id');?>";

            //if there is a session, ie this page has not been accessed via a public route
            if(sessionId !== ""){
                return sessionId === selectedValue;
            }
            return false;

        }

        function checkPrimaryNotSelectedContact(selectedValue){

            var primaryContact = "<?php echo session('primaryContact');?>";

            //if there is a session, ie this page has not been accessed via a public route
//            if(sessionId !== ""){
                return selectedValue === primaryContact;
//            }
//            return false;//extra measure to ensure if user not logged in cannot process payment


        }

        function editPrimaryContact() {
            console.log('radio changed');

            var inputArray = document.getElementsByClassName("primaryInput");//one for each user

            for (i = 0; i < inputArray.length; i++) {

                if (inputArray[i].checked === true) {

                    var selectedValue = inputArray[i].value;
                }
            }
            //if conditions met

            //1. if primary contact == selected user
            //do nothing
            var checkPrimarySelected = checkPrimaryNotSelectedContact(selectedValue);

            if (checkPrimarySelected === true) {

                //do not allow
                alert("cannot edit the contact as the selected contact is the primary contact already" + selectedValue);

                console.log("primary contact is the selected contact, so do not make a change");

            } else {

                //2. current user == selected user
                var result = checkSelectedIsCurrent(selectedValue);

                if (result === true) {

                    //present stripe config to user

                    //then
    //            document.getElementById("radioPrimary").submit();
                    alert("can edit the contact" + selectedValue);

                } else {
                    //alert to user that can only select yourself
                    // ie can only change the primary contact to the logged in user because credit card details are required
                    alert("cannot edit the contact" + selectedValue);

                }
            }

        }

        function updateCreditCard(){
            //conditions:
            //1. logged in user == primary contact

            //check if user is the primary contact first, else, redirect them to same page with an error msg
            var verifyUserSwap = verifyUserPrimaryContact();

            if (verifyUserSwap === true) {

                //present checkout widget to user if meets conditions
                alert("wip.");

            }else{

                //alert error msg to user that only the primary contact can update the credit card details.
                var errorPrimary = document.getElementById('error-primary');
                errorPrimary.style.display = "block";
            }

        }

        //verify the user is the primary contact before displaying the credit card widget
        //returns true if session user is the primary contact
        //or false if not the primary contact of not logged in
        function verifyUserPrimaryContact(){

            var sessionId = "<?php echo session('id');?>";

            var primaryContact = "<?php echo session('primaryContact');?>";

            //if there is a session, ie this page has not been accessed via a public route
            if(sessionId !== ""){
                return sessionId === primaryContact;
            }
            return false;//extra measure to ensure if user not logged in cannot process payment

        }

    </script>
@stop
@section('custom-styles')
    <style>
        /* Style the tab */
        body, .content-wrapper{
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
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }

        #error-primary{
            display: none;
        }

    </style>
@stop
@section('page-content')
    <div class="tab">
        <button class="tablinks" onclick="openSetting(event, 'Users')" id="loadPgTab">Users</button>
        <button class="tablinks" onclick="openSetting(event, 'Company')">Company</button>
        <button class="tablinks" onclick="openSetting(event, 'Subscription')">Subscription</button>
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

    {{--DEFAULT do not display, but is made visible if a non primary contact attempts to change credit card details--}}
    <div class="alert alert-danger margin-top" id="error-primary">
        Only the primary contact is authorized to update credit card details.
        The primary contact can be changed in settings>users.
    </div>

    <div id="Users" class="tabcontent col-md-12">

            <div style="padding:15px 0px 10px 0px;">
                <a href="/user/create" class="btn btn-success" style="margin-right: 3px;">Add User</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">

                    <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Primary Contact <button type="button" onclick="enableEditPrimary()"><i class="fa fa-edit"></i></button></th>
                        <th>Manage</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>

                            {{--Display a green checkmark for the primary contact--}}
                            <form action="/edit-primary-contact" method="POST" id="radioPrimary">

                                @if($user->role == "Manager")
                                    @if($user->user_id == $compInfo->contact->id)
                                        <td><input type="radio" name="primaryContact" value="{{$user->user_id}}" onchange="editPrimaryContact()"
                                                   class="primaryInput" disabled></td>
                                    @else
                                        <td><input type="radio" name="primaryContact" value="{{$user->user_id}}" onchange="editPrimaryContact()"
                                                   class="primaryInput" disabled></td>
                                    @endif
                                @else
                                    <td></td>
                                @endif

                            </form>
                            <td>
                                <a href="/user/{{ $user->user_id }}/edit"><i class="fa fa-edit"></i></a>
                                <a href="/confirm-delete/{{$user->user_id}}/{{$url}}" style="color: #990000;"><i class="fa fa-trash-o icon-padding"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>

        </div>
    </div>
    <div id="Company" class="tabcontent padding-top">
        <table class="table no-borders">
            <div class="col-md-10">

                <p class="nonlist-heading">
                    Company Name:
                </p>
                <p>
                    {{$compInfo->company->name}}
                </p>
                <br/>

                @if($compInfo->company->owner != null)
                    <p class="nonlist-heading">
                    Owner:
                    </p>
                    <p>
                        {{$compInfo->company->owner}}
                    </p>
                @endif
                <br/>

                <p class="nonlist-heading">
                    Primary Contact:
                </p>
                @if($compInfo->contact != null)
                    <p>
                        {{$compInfo->contact->first_name}} {{$compInfo->contact->last_name}}
                    </p>
                @else
                    <p>
                        Contact has been deleted via Settings>Users Page
                    </p>
                @endif
                <br/>

                <p class="nonlist-heading">
                    Contact Email:
                </p>
                @if($compInfo->contact != null)
                    <p>
                        {{$compInfo->contact->email}}
                    </p>
                @else
                    <p>
                        Contact has been deleted via Settings>Users Page
                    </p>
                @endif
                <br/>
            </div>
        </table>
    </div>

    <div id="Subscription" class="tabcontent padding-top padding-left-15">

        {{--Trial or Plan Details--}}
        @if(isset($trial))
            @if($trial === true)

                {{--on trial, not subscribed--}}
                <div class="alert alert-odin-info">
                    Kindly reminding you to subscribe to a plan before your free trial ends.
                </div>


                {{--todo: terms and conditions link--}}

                <div class="settings-sub-btns">

                    <button type="button" class="btn btn-success" onclick="window.location.href='/subscription/upgrade'"
                            id="upgradeBtn">Upgrade Plan
                    </button>

                    <button type="button" class="btn btn-info" onclick="updateCreditCard()">Update Credit Card
                    </button>

                    {{--todo: check with Nigel what to do here, cancel or contact us?--}}
                    <a href="/support/users" style="padding-left:10px;color:#333; font-size: smaller;"><span>Cancel Plan</span></a>

                    <img src="{{ asset("/bower_components/AdminLTE/dist/img/if_price-tag.png") }}" alt="subscription icon" class="page-icon"/>

                </div>

                <p class="nonlist-heading">Trial period ends:</p>

                <p>{{$trialEndsAt}}</p>
                <br/>
            @else
                {{--trial has ended, not subscribed--}}
                <div class="alert alert-danger">
                    Trial period ended! <br />
                    Kindly subscribe to a plan to continue using the application suite.
                </div>

                <div class="settings-sub-btns">

                    <button type="button" class="btn btn-success" onclick="window.location.href='/subscription/upgrade'"
                            id="upgradeBtn">Upgrade Plan
                        {{--todo: once subscriptions in place<button type="button" class="btn btn-success" onclick="window.location.href='/subscription/upgrade/{{$current}}'">Upgrade Plan--}}
                    </button>

                    {{--todo: check with Nigel what to do here, cancel or contact us?--}}
                    <a href="/support/users" style="padding-left:10px;color:#333; font-size: smaller;"><span>Cancel Plan</span></a>

                    <img src="{{ asset("/bower_components/AdminLTE/dist/img/if_price-tag.png") }}" alt="subscription icon" class="page-icon"/>

                </div>

                {{--<img src="{{ asset("/bower_components/AdminLTE/dist/img/if_price-tag.png") }}" alt="subscription icon" class="page-icon"/>--}}

            @endif
        @else
                @if(isset($subscriptionTerm))
                <div class="settings-sub-btns">

                    <button type="button" class="btn btn-success" onclick="window.location.href='/subscription/upgrade'"
                            id="upgradeBtn">Upgrade Plan
                    </button>

                    <button type="button" class="btn btn-info" onclick="updateCreditCard()">Update Credit Card
                    </button>

                    <a href="/support/users" style="padding-left:10px;color:#333; font-size: smaller;"><span>Cancel Plan</span></a>

                    <img src="{{ asset("/bower_components/AdminLTE/dist/img/if_price-tag.png") }}" alt="subscription icon" class="page-icon"/>

                </div>
                {{--<img src="{{ asset("/bower_components/AdminLTE/dist/img/if_price-tag.png") }}" alt="subscription icon" class="page-icon"/>--}}

                {{--active subscription--}}
                    <p class="nonlist-heading">Your Plan:</p>
                    <p>
                        {{ucfirst($numUsers)}}
                    </p>

                    <br/>

                    {{--Billing Cycle Details--}}

                    @if(isset($subscriptionTrial))
                        <p class="nonlist-heading">Billing Cycle:</p>

                        <p>{{ucwords($subscriptionTerm)}}</p>

                        <br/>
                        {{--Trial period Ends/Billing Begins--}}
                        <p class="nonlist-heading">Free Trial Period Ends and Billing Begins:
                            {{--Billing begins after remaining free trial days:--}}
                        </p>

                        <p>{{$subscriptionTrial}}</p>
                         <br/>
                    @else
                        <p class="nonlist-heading">Billing Cycle (nominated credit card charged at the end of each billing cycle):</p>

                        <p>{{ucwords($subscriptionTerm)}}</p>

                {{--todo: wip--}}
                        {{--<p>Credit card will have next payment deducted on:</p>--}}

                        <br/>
                    @endif

                @elseif(isset($subTermCancel))
                    {{--cancelled subscription--}}
                    <div class="alert alert-danger">
                        Subscription cancelled! <br />
                        Kindly subscribe to a plan to continue using the application suite.
                    </div>
                <div class="settings-sub-btns">

                    <button type="button" class="btn btn-success" onclick="window.location.href='/subscription/upgrade'"
                            id="upgradeBtn">Upgrade Plan
                        {{--todo: once subscriptions in place<button type="button" class="btn btn-success" onclick="window.location.href='/subscription/upgrade/{{$current}}'">Upgrade Plan--}}
                    </button>

                    {{--todo: check with Nigel what to do here, cancel or contact us?--}}
                    <a href="/support/users" style="padding-left:10px;color:#333; font-size: smaller;"><span>Cancel Plan</span></a>
                    <img src="{{ asset("/bower_components/AdminLTE/dist/img/if_price-tag.png") }}" alt="subscription icon" class="page-icon"/>

                </div>
                    {{--<img src="{{ asset("/bower_components/AdminLTE/dist/img/if_price-tag.png") }}" alt="subscription icon" class="page-icon"/>--}}
                @elseif(isset($subTermGrace))
                    {{--onGracePeriod--}}

                    <div class="alert alert-danger">
                        Subscription cancelled! <br />
                        Kindly subscribe to a plan to continue using the application suite beyond the grace period.
                    </div>
                <div class="settings-sub-btns">

                    <button type="button" class="btn btn-success" onclick="window.location.href='/subscription/upgrade'"
                            id="upgradeBtn">Upgrade Plan
                        {{--todo: once subscriptions in place<button type="button" class="btn btn-success" onclick="window.location.href='/subscription/upgrade/{{$current}}'">Upgrade Plan--}}
                    </button>

                    {{--todo: check with Nigel what to do here, cancel or contact us?--}}
                    <a href="/support/users" style="padding-left:10px;color:#333; font-size: smaller;"><span>Cancel Plan</span></a>
                    <img src="{{ asset("/bower_components/AdminLTE/dist/img/if_price-tag.png") }}" alt="subscription icon" class="page-icon"/>

                </div>
                    {{--<img src="{{ asset("/bower_components/AdminLTE/dist/img/if_price-tag.png") }}" alt="subscription icon" class="page-icon"/>--}}

                    <p class="nonlist-heading">Grace Period Ends:</p>
                    <p>{{$subTrialGrace}}</p>
                    <br/>

            @endif
        @endif

        {{--Primary Contact Details--}}
        <p class="nonlist-heading">
            Primary Contact Authorized to Upgrade Plan:
        </p>
        @if($compInfo->contact != null)
            <p>
                {{$compInfo->contact->first_name}} {{$compInfo->contact->last_name}}
            </p>
        @else
            <p>
                Contact has been deleted via Settings>Users Page
            </p>
        @endif
        {{--<br/>--}}

    </div>
@stop