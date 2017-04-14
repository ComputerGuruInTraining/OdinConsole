<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">

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
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                    <span class="input-group-btn">
                        <button type='submit' name='search' id='search-btn' class="btn btn-flat">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header"><h4>Quick Links</h></li>
            <!-- Optionally, you can add icons to the links -->
            <li class="active"><a href="#"><span>Dashboard</span></a></li>
            <li class="active"><a href="#"><span>Employees</span></a></li>
            <li class="active"><a href="#"><span>Roster</span></a></li>
            <li class="active"><a href="/locations"><span>Locations</span></a></li>
            <li class="active"><a href="#"><span>Clients</span></a></li>
            <li class="active"><a href="#"><span>Reports</span></a></li>
            <div class="list-divider"></div>
            <li class="header"><h4>My Links</h></li><!--TODO: v2: personalised links, at least most frequent by default-->
            <li class="active"><a href="#"><span>Create User</span></a></li>
            <li class="treeview">
                <a href="#"><span>View Map</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="#">Who's on Duty</a></li>
                    <li><a href="#">Locations</a></li>
                </ul>
            </li>

            <div class="list-divider"></div>
            <li class="header"><h4>@yield('my side-menu') Links</h4></li>
            <!-- Optionally, you can add icons to the links -->
            <li class="active"><a href="#"><span>Create</span></a></li>
            <li class="active"><a href="#"><span>Edit</span></a></li>
            <li class="active"><a href="/locations"><span>Delete</span></a></li>
        </ul>
        
        
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>