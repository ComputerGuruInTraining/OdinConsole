@extends('layouts.master_layout')
@extends('sidebar')
@include('layouts.functions_js')

@section('title-item') Settings @stop

@section('custom-scripts')

    <script src="https://checkout.stripe.com/checkout.js"></script>

    <script>

        var originalPrimary = null;
//        var newPrimary = null;

        //for subscription is active
        var subscriptionTerm = "<?php echo $subscriptionTerm?>";//either null or hold a value of monthly/yearly

        //open contact tab upon page load and show the tab as active
        window.addEventListener("load", function(){

            openSetting(null, 'Users');

            document.getElementById('loadPgTab').className += " active";

            getPrimaryContact();//set originalPrimary

        }, false);

//use the value in the input which will the primary contact as determined by database data
        function getPrimaryContact() {
            var inputArraySelected = document.getElementsByName('primaryContact');

            var sessionPrimary = "<?php echo session('primaryContact');?>";//fixme: error here? ensure session('primaryContact") updated effectively

            for (var x = 0; x < inputArraySelected.length; x++) {

                if (inputArraySelected[x].value === sessionPrimary) {

                    originalPrimary = inputArraySelected[x].value;//the current primary contact's user_id
// console.log('originalPrimary' + originalPrimary);
                    break;
                }
            }
        }

        //when open the tab, reset to original value so the user must select again, operates best for when cancel on modal selected or cancel checkout widget
        function resetRadio(){

            var inputArraySelected = document.getElementsByName('primaryContact');

            var sessionPrimary = "<?php echo session('primaryContact');?>";//fixme: error here? ensure session('primaryContact") updated effectively

            for (var x = 0; x < inputArraySelected.length; x++) {

                if (inputArraySelected[x].value === sessionPrimary) {

                    inputArraySelected[x].checked = true;//the current primary contact is selected upon tab open
// console.log('sessionPrimary' + sessionPrimary);
                    break;
                }
            }
        }

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

            if(evt !== null){
                evt.currentTarget.className += " active";
            }

            //hide the divs that shouldn't display until required
            hideDiv('error-custom-users');
            hideDiv('info-edit-active');
            hideDiv('info-edit-non-active');
            hideDiv('error-custom-users');

            //disabled the radio btns by default
            disableEditPrimary();

            resetRadio();
        }

        function hideDiv(id){
            var errorPrimary = document.getElementById(id);
            errorPrimary.style.display = "none";

        }

        function displayDiv(id){
            var errorPrimary = document.getElementById(id);
            errorPrimary.style.display = "block";

        }

        //option1: enable only for current user if active subscription
        //option2: enable for all users other than current primary contact
        function enableEditPrimary(){

            var inputArray = document.getElementsByName('primaryContact');//get all radio buttons

            if(subscriptionTerm !== "") {
                //current user
                var sessionId = "<?php echo session('id');?>";

                //only enable the current user's radio button
                for(var i = 0; i < inputArray.length; i++) {

                    //for the current user only
                    if (sessionId === inputArray[i].value) {
                        inputArray[i].removeAttribute('disabled');
                        break;
                    }
                }

                //show msg re edit primary contact for users with active subscription
                displayDiv('info-edit-active');

            }else{
                //enable all radio btns other than the current primary contact
                for (var a = 0; a < inputArray.length; a++) {

                    //for all users other than the current primary contact (value stored in OriginalPrimary)
                    if(inputArray[a].value !== originalPrimary)
                        inputArray[a].removeAttribute('disabled');

                }

                //show msg re edit primary contact for users without an active subscription
                displayDiv('info-edit-non-active');

            }
        }

        //disable all radio buttons
        function disableEditPrimary(){

            var inputArray = document.getElementsByName('primaryContact');

            for (var i = 0; i < inputArray.length; i++) {

                inputArray[i].setAttribute('disabled','disabled');
            }
        }

        //update credit card if the logged in user == primary contact
        function updateCreditCard(){

            //todo proper credit card update code
            alert("Update Credit Card is a Work in Progress. Please watch this space.");
            /*

            //check if user is the primary contact first, else, redirect them to same page with an error msg
            var verifyUserSwap = verifyUserPrimaryContact();

            if (verifyUserSwap === true) {

                //present checkout widget to user if meets condition
                stripeCheckout('updateCard');

            }else{

                displayDiv('error-custom-users');
            }*/
        }

        //conditions 1: only the current logged in user is enabled as an option, and only if the current logged in user
        // is a manager will they be able to edit

        //requirements 1/condition2: only current user can become the primary contact as entering credit card details next (dealt with by only enabling the current user's radio)
        //requirements 2: only users that role == manager are allowed to be the primary contact (dealt with on view)
        //requirements 3: only users that role == manager are allowed to change the primary contact (dealt with  on view)
        function editPrimaryContact() {

            var inputArray = document.getElementsByName('primaryContact');

            for (var i = 0; i < inputArray.length; i++) {

                if (inputArray[i].checked === true) {

                    var selectedValue = inputArray[i].value;
                    break;
                }
            }

            //put the new primary contact in the form input field
            var newPrimaryElem = document.getElementById('newPrimaryContact');
            newPrimaryElem.value = selectedValue;

            displayModal();

        }

        //Usage: 1.update credit card details ($0)
        // 2.edit primary contact (a:current subscription active: collect credit card details and create new subscription beginning when current subscription ends, so with trial days $0)
        //2.edit primary contact (c:onGracePeriod subscription: no, we will not bother creating and cancelling a new subscripton which is what this would entail,
        // rather we will leave subscription, then if the new primary contact opts to resume subscription (and get subscription should still display the graceperiod and take user to resume)
        // then we will instead of resuming in this ijnstance, we will create a new subscription with trial days equeal to grace period. )
        //2.edit primary contact (d:inTrial no subscription created yet: switch trial days to the new primary contact)

        //these scenarios, we will not in essence switch the subscription to the new primary contact
        //2.edit primary contact (b:cancelled subscription)
        //2.edit primary contact (e:notInTrial no subscription created yet)
        function stripeCheckout(feature) {

            var currency = 'USD';//todo: make user toggle and variable
            var logo = "<?php echo asset("/bower_components/AdminLTE/dist/img/odinlogoSm.jpg")?>";
            var company = "<?php echo Config::get('constants.COMPANY_NAME');?>";
            var key = "<?php echo Config::get('services.stripe.key');;?>";
            var userEmail = "<?php echo $email?>";

            //assign the token returned by checkout widget request to the hidden inputs on the forms
            var token = function (res) {

                if (feature === "updateCard") {
                    //update credit card route

                    var tokenRes = document.getElementById('stripeCardToken');
                    var tokenEmail = document.getElementById('stripeCardEmail');

                    tokenRes.value = res.id;
                    tokenEmail.value = res.email;

                    document.getElementById("updateCard").submit();

                } else {
                    //edit primary contact route
                    var tokenResEdit = document.getElementById('stripeEditToken');
                    var tokenEmailEdit = document.getElementById('stripeEditEmail');

                    tokenResEdit.value = res.id;
                    tokenEmailEdit.value = res.email;

                    document.getElementById("radioPrimary").submit();

                }
            };

            panelLabel = 'Submit Details';
            desc = 'Credit Card Details';

            StripeCheckout.open({
                key: key,
                name: company,
                email: userEmail,
                image: logo,
                description: desc,
                panelLabel: panelLabel,
                currency: currency,
                locale: 'auto',
                zipCode: true,
                token: token,
                closed: resetRadio,
            });

        }

        function displayModal(){

            var modalText = document.getElementById("modal-text");

            if(subscriptionTerm !== "") {

                modalText.style.display = "block";

            }else{
                modalText.style.display = "none";

            }

            // Get the modal
            var modal = document.getElementById("myModal2");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close-odin2")[0];

            // When the user clicks on the button, open the modal
            modal.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            }
            /**end Modal JS**/
        }

        function confirmEdit(){
            var modal = document.getElementById("myModal2");
            //close the modal and then submit the form
            modal.style.display = "none";

            //if current subscription, update the credit card details, else simply change the primary contact person
            if(subscriptionTerm !== "") {

                //present checkoutWidget
                stripeCheckout('editPrimary');

            }else{
                document.getElementById("radioPrimary").submit();//stripeEditToken & stripeEditEmail will be null

            }

        }

        function cancelEdit(){
            var modal = document.getElementById("myModal2");

            //close the modal, do not display checkout widget
            modal.style.display = "none";

            resetRadio();

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

        #error-custom-users, #info-edit-active, #info-edit-non-active{
            display: none;
        }

        #updateCard{
            width: 100px;
            display: inline;
        }

    </style>
@stop
@section('page-content')
    {{--Modal that displays when a user opts to swap a plan via public view pricing model, then logs in, and modal displays to confirm the swap--}}
    <div id="myModal2" class="modal2-odin">
        <div class="modal2-odin-content">
            <div class="modal2-odin-header">
                <span class="close-odin2">&times;</span>
                <h3>Change Primary Contact</h3>
            </div>
            <div class="modal2-odin-body">
                <span id="modal-text"><p>Changing the primary contact will transfer the subscription as well, which means the new primary contact's credit card details will need to be provided. </p>
                <p>The new credit card will be billed when the next bill is due (see Settings>Subscription for billing date).</p></span>
                <p class="padding-bottom-10">Kindly confirm you would like to change the primary contact?</p>
                <button type="button" class="btn btn-border" onclick="confirmEdit()">Confirm</button>
                <button type="button" class="btn btn-border" onclick="cancelEdit()">Cancel</button>
            </div>
        </div>
    </div>

    <div class="tab">
        <button class="tablinks" onclick="openSetting(event, 'Users')" id="loadPgTab">Users</button>
        <button class="tablinks" onclick="openSetting(event, 'Company')">Company</button>
        <button class="tablinks" onclick="openSetting(event, 'Subscription')">Subscription</button>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger padding margin-15">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{!! $error !!}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('status'))
        <div class="white-font alert alert-odin-success margin-top">
            {{ session('status') }}
        </div>
    @endif

    {{--DEFAULT do not display, but is made visible if a non primary contact attempts to change credit card details--}}
    <div class="alert alert-danger margin-15" id="error-custom-users">
        Only the primary contact is authorized to update credit card details.
        The primary contact can be changed via the users tab.
    </div>

    <div class="alert alert-odin-info margin-15" id="info-edit-active">
        To successfully change the primary contact: <br/><br/>
        *The primary contact is the only user authorized to manage subscriptions so we will require their credit card details as part of the process.<br/>
        *As we request credit card details, users are only able to change the primary contact to themselves.<br/>
        *If someone other than yourself is required to be the new primary contact, kindly ask them to make the change personally.<br/>
        *The primary contact's user role must be manager.
    </div>

    <div class="alert alert-odin-info margin-15" id="info-edit-non-active">
        To successfully change the primary contact: <br/><br/>
        *The primary contact's user role must be manager.
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

                        {{--Edit Primary Contact--}}
                        @if(session('role') == "Manager")
                            <th>Primary Contact
                                {{--fixme: atm do not include the change primary contact feature until all errors on active campaign and stripe that arise due to a change are corrected.--}}
                                <button type="button" id="editPrimaryBtn" style="display:none;" onclick="enableEditPrimary()"><i class="fa fa-edit"></i></button>
                            </th>
                        @else
                            <th>Primary Contact
                                {{--fixme atm do not include the change primary contact feature until all errors on active campaign and stripe that arise due to a change are corrected.--}}
                                <button type="button" id="editPrimaryBtn" style="display:none;" onclick="enableEditPrimary()" disabled><i class="fa fa-edit"></i></button>
                            </th>
                        @endif

                        <th>Manage</th>
                    </tr>
                    </thead>

                    <form action="/edit-primary-contact" method="POST" id="radioPrimary">
                        {{ csrf_field() }}

                        <input type="hidden" name="newPrimaryContact" value="" id="newPrimaryContact"/>
                        <input type="hidden" name="stripeEditToken" value="" id="stripeEditToken"/>
                        <input type="hidden" name="stripeEditEmail" value="" id="stripeEditEmail"/>

                        <tbody>

                        @foreach ($users as $user)

                            <tr>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>

                                {{--Primary Contact radio--}}
                                @if($user->role == "Manager")
                                    @if($user->user_id == $compInfo->contact->id)
                                        <td>{{ Form::radio('primaryContact', $user->user_id, true, [
                                        'onchange' => 'editPrimaryContact()', 'disabled' => 'disabled'
                                        ])}}</td>
                                    @else
                                        <td>{{ Form::radio('primaryContact', $user->user_id, false, [
                                        'onchange' => 'editPrimaryContact()',  'disabled' => 'disabled'
                                        ])}}</td>
                                    @endif
                                @else
                                    <td></td>
                                @endif

                                <td>
                                    <a href="/user/{{ $user->user_id }}/edit"><i class="fa fa-edit"></i></a>
                                    @if(session('id') != $user->user_id)
                                        <a href="/confirm-delete/{{$user->user_id}}/{{$url}}" style="color: #990000;"><i class="fa fa-trash-o icon-padding"></i></a>
                                    @else
                                        <i class="fa fa-trash-o icon-padding"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </form>

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
                    <br/>
                @endif

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

        {{--alert msgs to user at top of view--}}
        @if(isset($trial))
            @if($trial === true)

                {{--on trial, not subscribed--}}
                <div class="alert alert-odin-info">
                    Kindly reminding you to subscribe to a plan before your free trial ends.
                </div>
            @else
                {{--trial has ended, not subscribed--}}
                <div class="alert alert-danger">
                    Trial period ended! <br />
                    Kindly subscribe to a plan to continue using the application suite.
                </div>
            @endif
        @else
            @if(isset($subTermCancel))
                {{--cancelled subscription--}}

                <div class="alert alert-danger">
                    Subscription cancelled! <br />
                    Kindly subscribe to a plan to continue using the application suite.
                </div>
            @elseif(isset($subTermGrace))
                {{--onGracePeriod--}}

                <div class="alert alert-danger">
                    Subscription cancelled! <br />
                    Kindly subscribe to a plan to continue using the application suite beyond the grace period.
                </div>
            @endif
        @endif

        {{--page icon--}}
        <img src="{{ asset("/bower_components/AdminLTE/dist/img/if_price-tag.png") }}" alt="subscription icon" class="page-icon"/>

        {{--buttons--}}
        <div class="settings-sub-btns">

            {{--todo: terms and conditions link--}}
            <button type="button" class="btn btn-success" onclick="window.location.href='/subscription/upgrade'"
                    id="upgradeBtn">Upgrade Plan
            </button>

            {{--display option to update credit card details for current subscriptions--}}
            @if(isset($subscriptionTerm))
                <!--<form id="updateCard" action="/update-credit-card" method="POST" type="hidden">
                    {{ csrf_field() }}

                    <input type="hidden" name="stripeCardToken" value="" id="stripeCardToken"/>
                    <input type="hidden" name="stripeCardEmail" value="" id="stripeCardEmail"/>
                    <button type="button" class="btn btn-info" onclick="updateCreditCard()">Update Credit Card
                    </button>
                </form>-->

                {{--todo: check with Nigel what to do here, cancel or contact us?--}}

                <a href="/support/users" style="padding-left:10px;color:#333; font-size: smaller;"><span>Cancel Plan</span></a>
            @endif

        </div>


        {{--Trial or Plan Details--}}
            @if(isset($trial))
                <p class="nonlist-heading">Trial period ends:</p>

                <p>{{$trialEndsAt}}</p>
                <br/>
            @else
                @if(isset($subscriptionTerm))

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

                @elseif(isset($subTermGrace))
                    {{--onGracePeriod--}}
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
    </div>
@stop