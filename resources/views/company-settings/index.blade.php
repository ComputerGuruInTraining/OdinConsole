@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item') Settings @stop

@section('custom-scripts')
    <script>
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
    </style>
@stop
@section('page-content')
    <div class="tab">
        <button class="tablinks" onclick="openCity(event, 'Users')">Users</button>
        <button class="tablinks" onclick="openCity(event, 'Paris')">Primary Company Contact</button>
        {{--<button class="tablinks" onclick="openCity(event, 'Tokyo')">My Profile Settings</button>--}}
    </div>

    <div id="Users" class="tabcontent col-md-12">

        {{--<div class=>--}}

            <div style="padding:15px 0px 10px 0px;">
                <button type="button" class="btn btn-success" onclick="window.location.href='user/create'">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add User
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">

                    <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Action</th>
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
                                <a href="/user/{{ $user->user_id }}/edit" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
                                {{ Form::open(['url' => '/user/' . $user->user_id, 'method' => 'DELETE']) }}
                                {{ Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                                {{ Form::close() }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            {{--</div>--}}

        </div>
    </div>
    <div id="Paris" class="tabcontent">
        <h3>Primary Company Contact</h3>
        <p>Name: </p>
        <p>Email: </p>
    </div>

    {{--<div id="Tokyo" class="tabcontent">--}}
    {{--<h3>Tokyo</h3>--}}
    {{--<p>Tokyo is the capital of Japan.</p>--}}
    {{--</div>--}}
    {{--</div>--}}

@stop