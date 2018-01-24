<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar header-custom">

    <!-- sidebar: style can be found in sidebar.less and AdminLTE.min.css
    included in project via /bower_components/adminlte/dist/css/AdminLTE.min.css-->

    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <!--<div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset("/bower_components/adminlte/dist/img/avatar3.png") }}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>Admin Team</p>
                <!-- Status -->
                <!--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>-->

        <!-- search form (Optional) -->
        {{--<form action="#" method="get" class="sidebar-form">--}}
            {{--<div class="input-group">--}}
                {{--<input type="text" name="q" class="form-control" placeholder="Search..."/>--}}
                    {{--<span class="input-group-btn">--}}
                        {{--<button type='submit' name='search' id='search-btn' class="btn btn-flat">--}}
                            {{--<i class="fa fa-search"></i>--}}
                        {{--</button>--}}
                    {{--</span>--}}
            {{--</div>--}}
        {{--</form>--}}
        <!-- /.search form -->

        {{--stlyesheet used for the add button--}}
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <script>

            function createOptions(){



            }

        </script>

        <!-- Sidebar Menu -->
            <div class="header">


                <!-- Add Dropdown-->
                <div class="dropdown">
                    <p class="menu-heading">Quick Links</p>
                    <button class="w3-button w3-circle btn-success dropdown-toggle"
                            id="menu1" type="button" data-toggle="dropdown"><span>+</span></button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        {{--<li role="presentation"><a role="menuitem" tabindex="-1" href="#" style="font-weight: bold;">Create</a></li>--}}
                        {{--<li role="presentation" class="divider"></li>--}}
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/employees/create">Add Employee</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/employee/create-existing">Add Existing User as Employee</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/location-create">Add Location</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/rosters/create">Add Shift</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/case-notes/create">Case Note</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/reports/create">Generate Report</a></li>
                    </ul>
                </div>
            </div>

        <ul class="sidebar-menu">

        <!-- Optionally, you can add icons to the links -->
            <li class="active"><a href="/map-geolocation" class="menu"><span>Who's on Duty</span></a></li>
            <li class="active"><a href="/employees" class="menu"><span>Employees</span></a></li>
            <li class="active"><a href="/location" class="menu"><span>Locations</span></a></li>
            <li class="active"><a href="/rosters" class="menu"><span>Shifts</span></a></li>
            <li class="active"><a href="/case-notes" class="menu">Case Notes</a></li>
            <li class="active"><a href="/reports" class="menu"><span>Reports</span></a></li>

            <div class="list-divider"></div>

            {{--FIXME: sidebar toggle operation - SOLUTION: routes need to be in the form "employees-create" not "employees/create" except this causes a problem elsewhere --}}
            <li class="treeview">
                <a href="/support/users"><span>Support</span></a>
                <a href="/settings"><span>Settings</span></a>
                <a href="/logout"><span>Sign Out</span></a>
            </li>


            {{--<!--TODO: v2: personalised links, created by user and most frequent links as the default in this section until personalization-->--}}
            {{--<li class="header"><h4>My Links</h4></li>--}}
            {{--<li class="active"><a href="/location"><span>Locations</span></a></li>--}}
            {{--<li class="treeview">--}}
                {{--<a href="/location"><span>Locations</span> <i class="fa fa-angle-left pull-right"></i></a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li><a href="/location-create">Create Location</a></li>--}}
                {{--</ul>--}}
            {{--</li>--}}
            {{--<div class="list-divider"></div>--}}

        </ul>


        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
