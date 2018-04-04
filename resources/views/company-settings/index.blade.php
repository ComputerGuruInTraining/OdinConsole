@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item') Settings @stop

@section('custom-scripts')
    <script>

        //open contact tab upon page load and show the tab as active
        window.addEventListener("load", function(){
            openSetting(event, 'Users');

            document.getElementById('loadPgTab').className += " active";

//            enableUpgradeBtn();

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

        /*.alert-danger{*/
            /*border-color: #d73925;*/
            /*color: #fff !important;*/
            /*background-color: #990000 !important;*/
        /*}*/

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

            {{--<tr>--}}
                {{--<th class="col-md-2">--}}
                    {{--Company Name:--}}
                {{--</th>--}}
                {{--<td>--}}
                    {{--{{$compInfo->company->name}}--}}
                {{--</td>--}}
            {{--</tr>--}}
            {{--@if($compInfo->company->owner != null)--}}
                {{--<tr>--}}
                    {{--<th>--}}
                        {{--Owner:--}}
                    {{--</th>--}}
                    {{--<td>--}}
                        {{--{{$compInfo->company->owner}}--}}
                    {{--</td>--}}
                {{--</tr>--}}
            {{--@endif--}}
            {{--<tr>--}}
                {{--<th>--}}
                    {{--Primary Contact:--}}
                {{--</th>--}}
                {{--@if($compInfo->contact != null)--}}
                    {{--<td>--}}
                        {{--{{$compInfo->contact->first_name}} {{$compInfo->contact->last_name}}--}}
                    {{--</td>--}}
                {{--@else--}}
                    {{--<td>--}}
                        {{--Contact has been deleted via Settings>Users Page--}}
                    {{--</td>--}}
                {{--@endif--}}
            {{--</tr>--}}
            {{--<tr>--}}
                {{--<th>--}}
                    {{--Contact Email:--}}
                {{--</th>--}}
                {{--@if($compInfo->contact != null)--}}
                    {{--<td>--}}
                        {{--{{$compInfo->contact->email}}--}}
                    {{--</td>--}}
                {{--@else--}}
                    {{--<td>--}}
                        {{--Contact has been deleted via Settings>Users Page--}}
                    {{--</td>--}}
                {{--@endif--}}
            {{--</tr>--}}
            </div>
        </table>
    </div>

    <div id="Subscription" class="tabcontent padding-top">
        <img src="{{ asset("/bower_components/AdminLTE/dist/img/if_price-tag.png") }}" alt="subscription icon" class="page-icon"/>

        {{--        The {{Config::get('constants.BASIC_PLAN')}} plan--}}
        {{--<br/>--}}
        {{--<br/>--}}
        {{--<a href='http://odincasemanagement.com/#pricing'>--}}
            {{--<img src="{{ asset("/images/PricingModel_2018-03-07.png") }}"  width="55%" height="55%"--}}
                 {{--alt="Pricing Model courtesy of Odin Case Management Marketing Team" />--}}
        {{--</a>--}}
        {{--<br/>--}}
        {{--<br/>--}}
        {{--@if(gettype($subscriptionStatus) == "object")--}}
            {{--fixme subscription exists but may be out of date at this point no check--}}
        {{--@elseif(gettype($subscriptionStatus) == "boolean")--}}
            {{--trial variable has been returned, could be true or false--}}

        {{--Trial or Plan Details--}}
        @if(isset($trial))
            @if($trial === true)
                {{--on trial, not subscribed--}}
                <p class="nonlist-heading">Trial period ends:</p>

                <p>{{$trialEndsAt}}</p>
            @else

                @if(!isset($numUsers))
                        {{--not on trial, not on subscription--}}

                @else
                    {{--on subscription--}}
                    <p class="nonlist-heading">Your Plan:</p>
                    {{--todo: variable--}}
                    <p>
                        {{$numUsers}}
                    </p>

                    <br/>

                    {{--Billing Cycle Details--}}

                    <p class="nonlist-heading">Billing Cycle:</p>
                    {{--todo: variable--}}

                    <p>{{$chosenTerm}}</p>
                @endif
            @endif
        @endif

        <br/>


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
        <br/>

        <div style="padding:15px 0px 10px 0px;">

            <button type="button" class="btn btn-success" onclick="window.location.href='/subscription/upgrade'"
                    id="upgradeBtn">Upgrade Plan
                {{--todo: once subscriptions in place<button type="button" class="btn btn-success" onclick="window.location.href='/subscription/upgrade/{{$current}}'">Upgrade Plan--}}
            </button>

        </div>

    </div>

@stop