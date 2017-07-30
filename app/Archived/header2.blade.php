<!-- Main Header -->
<header class="main-header" style="background-color:#ffffff;">

    <!-- Logo -->
    {{--<a href="index2.html" class="logo"><b>Odin Lite Console</a>--}}
    {{--<div class="pull-left image" style="max-width:200px;">--}}
        {{--<img src="{{ asset("/bower_components/adminlte/dist/img/ODIN-Logo.png") }}" class="odin-logo" alt="Odin Logo" />--}}
    {{--</div>--}}

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <div>
            <span class="active"><a href="/admin"><span>Dashboard</span></a></span>
            <span class="active"><a href="/employees"><span>Employees</span></a></span>
            <span class="active"><a href="/rosters"><span>Roster</span></a></span>
            <span class="active"><a href="/reports"><span>Reports</span></a></span>


        </div>
        <!-- Sidebar toggle button-->
        <!--FIXME: menu toggle button is not visible after first being pressed.
        Probably started occurring after odin logo added to header. -->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                {{--<div class="sidebar-menu">--}}
                    {{--<div class="header"><h4>Quick Links</h4></div>--}}
                    <!-- Optionally, you can add icons to the links -->



                    {{--<div class="list-divider"></div>--}}

                    <!--TODO: v2: personalised links, created by user and most frequent links as the default in this section until personalization-->
                    {{--<li class="header"><h4>My Links</h4></li>--}}
                    {{--<li class="active"><a href="#"><span>Create User</span></a></li>--}}
                    {{--<li class="treeview">--}}
                    {{--<a href="#"><span>Map</span> <i class="fa fa-angle-left pull-right"></i></a>--}}
                    {{--<ul class="treeview-menu">--}}
                    {{--<li><a href="#">Who's on Duty</a></li>--}}
                    {{--<li><a href="#">View My Locations</a></li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--<div class="list-divider"></div>--}}

                {{--</div>--}}
                <!-- Messages: style can be found in dropdown.less-->
                {{--<li class="dropdown messages-menu">--}}
                    {{--<!-- Menu toggle button -->--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                        {{--<i class="fa fa-envelope-o"></i>--}}
                        {{--<span class="label label-success">4</span>--}}
                    {{--</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li class="header">You have 4 messages</li>--}}
                        {{--<li>--}}
                            {{--<!-- inner menu: contains the messages -->--}}
                            {{--<ul class="menu">--}}
                                {{--<li><!-- start message -->--}}
                                    {{--<a href="#">--}}
                                        {{--<div class="pull-left">--}}
                                            {{--<!-- User Image -->--}}
                                            {{--<img src="{{ asset("/bower_components/adminlte/dist/img/avatar3.png") }}" class="img-circle" alt="User Image"/>--}}
                                        {{--</div>--}}
                                        {{--<!-- Message title and timestamp -->--}}
                                        {{--<h4>--}}
                                            {{--Support Team--}}
                                            {{--<small><i class="fa fa-clock-o"></i> 5 mins</small>--}}
                                        {{--</h4>--}}
                                        {{--<!-- The message -->--}}
                                        {{--<p>Why not buy a new awesome theme?</p>--}}
                                    {{--</a>--}}
                                {{--</li><!-- end message -->--}}
                            {{--</ul><!-- /.menu -->--}}
                        {{--</li>--}}
                        {{--<li class="footer"><a href="#">See All Messages</a></li>--}}
                    {{--</ul>--}}
                {{--</li><!-- /.messages-menu -->--}}

                {{--<!-- Notifications Menu -->--}}
                {{--<li class="dropdown notifications-menu">--}}
                    {{--<!-- Menu toggle button -->--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                        {{--<i class="fa fa-bell-o"></i>--}}
                        {{--<span class="label label-warning">10</span>--}}
                    {{--</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li class="header">You have 10 notifications</li>--}}
                        {{--<li>--}}
                            {{--<!-- Inner Menu: contains the notifications -->--}}
                            {{--<ul class="menu">--}}
                                {{--<li><!-- start notification -->--}}
                                    {{--<a href="#">--}}
                                        {{--<i class="fa fa-users text-aqua"></i> 5 new members joined today--}}
                                    {{--</a>--}}
                                {{--</li><!-- end notification -->--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="footer"><a href="#">View all</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                {{--<!-- Tasks Menu -->--}}
                {{--<li class="dropdown tasks-menu">--}}
                    {{--<!-- Menu Toggle Button -->--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                        {{--<i class="fa fa-flag-o"></i>--}}
                        {{--<span class="label label-danger">9</span>--}}
                    {{--</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li class="header">You have 9 tasks</li>--}}
                        {{--<li>--}}
                            {{--<!-- Inner menu: contains the tasks -->--}}
                            {{--<ul class="menu">--}}
                                {{--<li><!-- Task item -->--}}
                                    {{--<a href="#">--}}
                                        {{--<!-- Task title and progress text -->--}}
                                        {{--<h3>--}}
                                            {{--Design some buttons--}}
                                            {{--<small class="pull-right">20%</small>--}}
                                        {{--</h3>--}}
                                        {{--<!-- The progress bar -->--}}
                                        {{--<div class="progress xs">--}}
                                            {{--<!-- Change the css width attribute to simulate progress -->--}}
                                            {{--<div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">--}}
                                                {{--<span class="sr-only">20% Complete</span>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</a>--}}
                                {{--</li><!-- end task item -->--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="footer">--}}
                            {{--<a href="#">View all tasks</a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{ asset("/bower_components/adminlte/dist/img/avatar3.png") }}" class="user-image" alt="User Image"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{session('name')}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ asset("/bower_components/adminlte/dist/img/avatar3.png") }}" class="img-circle" alt="User Image" />
                            {{--<p>--}}
                                {{--Admin Team--}}
                                {{--<small>Member since 2017</small>--}}
                            {{--</p>--}}
                        </li>
                        <!-- Menu Body -->
                        {{--<li class="user-body">--}}
                            {{--<div class="col-xs-4 text-center">--}}
                                {{--<a href="#">Followers</a>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-4 text-center">--}}
                                {{--<a href="#">Sales</a>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-4 text-center">--}}
                                {{--<a href="#">Friends</a>--}}
                            {{--</div>--}}
                        {{--</li>--}}
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="/company/settings" class="btn btn-default btn-flat">Settings</a>
                            </div>
                            <div class="pull-right">
                                <a href="/logout" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>