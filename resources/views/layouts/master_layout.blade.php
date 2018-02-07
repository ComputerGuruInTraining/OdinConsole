<!DOCTYPE html>
<html lang='en'>
<head>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>@yield('title')</title>
    {{--Bootstrap Cdn stylesheet--}}
    <link rel='stylesheet' href='//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css'>
    <!-- Font Awesome -->
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

    {{--Font Family by Google--}}
    <link href='https://fonts.googleapis.com/css?family=Sofia' rel='stylesheet'>

    {{--Bootstrap stylesheet--}}
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
    {{--<link rel="{{ asset("/bower_components/adminlte/dist/css/skins/skin-blue.min.css")}}" type="text/css">--}}

    {{--necessary at least for date picker, if not other features--}}
    {{--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">--}}
    {{--<link rel="stylesheet" href="/resources/demos/style.css">--}}


    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    {{--<script type="text/javascript"--}}
            {{--src="{{asset('/bower_components/adminlte/plugins/jQuery/jquery-3.2.1.js')}}"></script>--}}


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

    <script>
        window.addEventListener("onkeydown", function(e){
            console.log("onkeydown called");
            if (e.keyCode == 27) {
                hideLoading();
            }
        });
    </script>

    {{--Company Settings Page & logged in support page ie --}}
    @yield('custom-scripts')

    @yield('custom-styles')

</head>

<body class="hold-transition skin-blue sidebar-mini">
<!-- display/hide loading on page navigate/load-->
<script>

//    jQuery( 'a' )
//        .click(function() {
//            displayLoader();
//            return false;
//        });
//
//    $(document).on('click', 'button', function () {
//        displayLoader();
//    });


//listed in order of execution

    window.addEventListener("load", hideLoading());

//    window.addEventListener("abort", hideLoading());

//    window.addEventListener("unload", hideLoading());



//    $(document).keydown(function(e) {
//        if (e.keyCode == 27) { // escape key maps to keycode `27`
//            hideLoading();
//        }
//    });


    function displayLoader(){

        var loader = document.getElementsByClassName('loader')[0];
        loader.style.display = "block";

//        var transparentBG = document.getElementsByTagName('body')[0];
//        transparentBG.style.opacity = 0.1;
    }

    function hideLoading(){
        console.log("hideLoading called");
        var loader = document.getElementsByClassName('loader')[0];

        if(loader !== undefined) {
            loader.style.display = "none";
            //        var transparentBG = document.getElementsByTagName('body')[0];
            //        transparentBG.style.opacity =
        }

    }

</script>
<div class='container-fluid'>
    <div class="wrapper">
    {{--FIXME: scroll-bar change height as a bit buggy--}}
    <!-- Main Header
            <header class="main-header">  -->
    @include('header')

    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

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
