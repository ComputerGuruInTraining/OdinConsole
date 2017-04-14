<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>@yield('title') | User Admin</title>
        <link rel='stylesheet' href='//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css'>
                <!-- Font Awesome -->
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        <!--<link rel="stylesheet" href="{{ asset("/bower_components/adminlte/bootstrap/css/bootstrap.min.css") }}" type="text/css">-->
        <!-- Font Awesome -->
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">-->
        <!-- Ionicons -->
        <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset("/bower_components/adminlte/dist/css/AdminLTE.css")}}" type="text/css">
        <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
                page. However, you can choose any other skin. Make sure you
                apply the skin class to the body tag so the changes take effect.
        -->
        <link rel="{{ asset("/bower_components/adminlte/dist/css/skins/skin-blue.min.css")}}" type="text/css" >

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
            <!--<body>
    <div class='container-fluid'>-->
        <div class="wrapper">

            <!-- Main Header 
            <header class="main-header">  -->
            @include('header')

            <!-- Sidebar -->
            @include('sidebar')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header 1 -->
                <section class="content-header">
                <h1>
                    @yield('title-item')
                    <!--<small> {{$page_description or null}} </small>-->
                </h1>
                <!-- You can dynamically generate bread crumb here -->
                <!--<ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                    <li class="active">Here</li>
                </ol>-->
                </section>
                <!-- Main content -->
                <section class="content">
                <!-- Your Page Content Here -->
                @yield('content-item')
                </section>
                <!-- Content Header 2 -->
                <section class="content-header">
                <h1>
                    @yield('title-list')
                </h1>
                </section>
                <section class="content">
                @yield('content-list')
                </section>
                <!-- /.content -->
            </div>
        <!-- /.content-wrapper -->


            <!-- Footer -->
            @include('footer')

        </div>
        <!-- ./wrapper -->


        <!-- REQUIRED JS SCRIPTS -->

        <!-- jQuery 2.2.3 -->
        <script src="bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="bower_components/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
        <!-- AdminLTE App -->
        <script src="bower_components/AdminLTE/dist/js/app.min.js"></script>

        <!-- Optionally, you can add Slimscroll and FastClick plugins.
            Both of these plugins are recommended to enhance the
            user experience. Slimscroll is required when using the
            fixed layout. -->
    </body>
            <!--<div class='row'>
                @yield('content')
            </div>
        </div>
    </body>-->
</html>