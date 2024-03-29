<!DOCTYPE html>
<html lang='en'>
<head>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>@yield('title')</title>
    <!-- Bootstrap Cdn stylesheet-->
    <link rel='stylesheet' href='//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css'>
    <!-- Font Awesome -->
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

<!-- Font Family by Google-->
    <link href='https://fonts.googleapis.com/css?family=Sofia' rel='stylesheet'>

    <!-- Bootstrap stylesheet-->
    <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css") }}"
          type="text/css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/dist/css/AdminLTE.css")}}" type="text/css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
            page. However, you can choose any other skin. Make sure you
            apply the skin class to the body tag so the changes take effect.
    -->

    <link rel="stylesheet" href="{{asset('css/main.css')}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript">
        function noenter() {
            return !(window.event && window.event.keyCode == 13);
        }
    </script>

    <!--Company Settings Page & logged in support page ie -->
    @yield('custom-scripts')

    @yield('custom-styles')

</head>

<body class="hold-transition skin-blue sidebar-mini">
<!-- display/hide loading on page navigate/load-->
<script>

//listed in order of execution
    window.addEventListener("load", hideLoading());

    function displayLoader(){

        var loader = document.getElementsByClassName('loader')[0];
        loader.style.display = "block";
    }

    function hideLoading(){
        var loader = document.getElementsByClassName('loader')[0];

        if(loader !== undefined) {
            loader.style.display = "none";

        }
    }

</script>
<div class='container-fluid'>
    <div class="wrapper">
        <!-- FIXME: scroll-bar change height as a bit buggy-->
        <!-- Main Header
                <header class="main-header">  -->
    @include('header')

    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <section class="content-header">
                <h1>
                @yield('title-item')
                </h1>
            </section>
            <div class="loader"></div>

            @yield('page-content')
        </div>
        <!-- /.content-wrapper -->
    </div>
    <!-- ./wrapper -->
    <!-- Footer -->
    @include('footer')

    <!-- REQUIRED JS SCRIPTS -->
    <!-- jQuery-->
    <script src="bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>

    <!-- Bootstrap 3.3.6 -->
    <script src="bower_components/AdminLTE/bootstrap/js/bootstrap.min.js"></script>

    <!-- AdminLTE App --><!-- This file controls sidebar toggle functionality-->
    <script src="bower_components/AdminLTE/dist/js/app.min.js"></script>

    <!-- Optionally, you can add Slimscroll and FastClick plugins.
        Both of these plugins are recommended to enhance the
        user experience. Slimscroll is required when using the
        fixed layout. -->

</div>
</body>
</html>
