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

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header"><p class="menu-heading">Quick Links</p></li>
            <!-- Optionally, you can add icons to the links -->
            <li class="active"><a href="/map-geolocation" class="menu"><span>Who's on Duty</span></a></li>
            <li class="active"><a href="/employees" class="menu"><span>Employees</span></a></li>
            <li class="active"><a href="/rosters" class="menu"><span>Roster</span></a></li>
            <li class="active"><a href="/location" class="menu"><span>Locations</span></a></li>
            <li class="active"><a href="/reports" class="menu"><span>Reports</span></a></li>

            <div class="list-divider"></div>

            {{--FIXME: sidebar - SOLUTION: routes need to be in the form "employees-create" not "employees/create"--}}
            {{--<li class="treeview">--}}
                {{--<a href="/locations"><span>Locations</span> <i class="fa fa-angle-left pull-right"></i></a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li><a href="location-add">Add Location</a></li>--}}
                    {{--<li><a href="#">Edit Location</a></li>--}}
                {{--</ul>--}}
            {{--</li>--}}
            {{--<!--TODO: v2: personalised links, created by user and most frequent links as the default in this section until personalization-->--}}
            {{--<li class="header"><h4>My Links</h4></li>--}}
            {{--<li class="active"><a href="#"><span>Create User</span></a></li>--}}
            {{--<li class="treeview">--}}
                {{--<a href="/employing"><span>Employee Create</span> <i class="fa fa-angle-left pull-right"></i></a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li><a href="locations">Location</a></li>--}}
                    {{--<li><a href="#">Edit Location</a></li>--}}
                {{--</ul>--}}
            {{--</li>--}}
            {{--<div class="list-divider"></div>--}}

        </ul>


        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
