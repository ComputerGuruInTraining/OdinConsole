@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item') Settings @stop

@section('custom-scripts')
    <script>

        //open contact tab upon page load and show the tab as active
        window.addEventListener("load", function(){
            openCity(event, 'Users');

            document.getElementById('loadPgTab').className += " active";

        }, false);

        function openCity(evt, cityName) {
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
            document.getElementById(cityName).style.display = "block";
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
        <button class="tablinks" onclick="openCity(event, 'Users')" id="loadPgTab">Users</button>
        <button class="tablinks" onclick="openCity(event, 'Company')">Company Info</button>
        {{--<button class="tablinks" onclick="openCity(event, 'Tokyo')">My Profile Settings</button>--}}
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
                        <th>Manage</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            {{--<td>{{ $user->created_at->format('F d, Y h:ia') }}</td>--}}
                            <td>
                                <a href="/user/{{ $user->user_id }}/edit">Edit</a>
                                |
                                <a href="/confirm-delete/{{$user->user_id}}/{{$url}}" style="color: #990000;">Delete</a>
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
            <tr>
                <th class="col-md-2">
                    Company Name:
                </th>
                <td>
                    {{$compInfo->company->name}}
                </td>
            </tr>
            <tr>
                <th>
                    Owner:
                </th>
                <td>
                    {{$compInfo->company->owner}}
                </td>
            </tr>
            <tr>
                <th>
                    Primary Contact:
                </th>
                @if($compInfo->contact != null)
                    <td>
                        {{$compInfo->contact->first_name}} {{$compInfo->contact->last_name}}
                    </td>
                @else
                    <td>
                        Contact has been deleted via Settings>Users Page
                    </td>
                @endif
            </tr>
            <tr>
                <th>
                    Contact Email:
                </th>
                @if($compInfo->contact != null)
                <td>
                    {{$compInfo->contact->email}}
                </td>
                @else
                    <td>
                        Contact has been deleted via Settings>Users Page
                    </td>
                @endif
            </tr>
            </div>
        </table>
    </div>

    {{--<div id="Tokyo" class="tabcontent">--}}
    {{--<h3>Tokyo</h3>--}}
    {{--<p>Tokyo is the capital of Japan.</p>--}}
    {{--</div>--}}
    {{--</div>--}}

@stop