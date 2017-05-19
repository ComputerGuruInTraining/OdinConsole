<!DOCTYPE html>
<html lang='en'>
<head>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>@yield('title')</title>
    {{--Bootstrap Cdn stylesheet--}}
    <link rel='stylesheet' href='//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css'>
    <!-- Font Awesome -->
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    {{--Bootstrap stylesheet--}}
    <link rel="stylesheet" href="{{ asset("/bower_components/adminlte/bootstrap/css/bootstrap.min.css") }}" type="text/css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("/bower_components/adminlte/dist/css/AdminLTE.css")}}" type="text/css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
            page. However, you can choose any other skin. Make sure you
            apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="{{ asset("/bower_components/adminlte/dist/css/skins/skin-blue.min.css")}}" type="text/css" >

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">

    {{--Clock Picker Stylesheet and Scripts--}}
    {{--see source https://weareoutman.github.io/clockpicker/jquery.html--}}
    <link rel="stylesheet" href="{{ asset('/bower_components/adminlte/plugins/clockpicker/css/jquery-clockpicker.min.css')}}" type="text/css">
    <script type="text/javascript" src="{{asset('/bower_components/adminlte/plugins/jQuery/jquery-3.2.1.js')}}"></script>
    <script type="text/javascript" src="{{asset('/bower_components/adminlte/plugins/clockpicker/js/jquery-clockpicker.min.js')}}"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript">
        function noenter() {
            return !(window.event && window.event.keyCode == 13); }
    </script>

</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class='container-fluid'>
    <div class="wrapper">
    {{--FIXME: scroll-bar change height as a bit buggy--}}
    {{--FIXME: when press menu icon if menu not showing depending on device size, sometimes doesn't show--}}
    <!-- Main Header
            <header class="main-header">  -->
    @include('header')

    {{--<!-- Sidebar -->--}}
    {{--@include('sidebar')--}}

    <!-- Content Wrapper. Contains page content -->
        {{--FIXME: v1 Page content requires a bigger height than current style as footer overlaps--}}
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
            @yield('page-content')
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
        @include('footer')

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

    <!-- jQuery-->
    <script src="bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>

    <!-- Bootstrap 3.3.6 -->
    <script src="bower_components/AdminLTE/bootstrap/js/bootstrap.min.js"></script>

    <!-- AdminLTE App -->
    <script src="bower_components/AdminLTE/dist/js/app.min.js"></script>

    {{-- Date of birth Picker --}}
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $( function() {

            $( ".datepicker" ).datepicker({
                changeMonth: true,               
                changeYear: true,
                yearRange: "-80:+0"
            });

        } );
    </script>

    {{--Clock Picker--}}
    {{--to include the clock picker on a page, use a html input field. See rosters/create for eg--}}
    <script type="text/javascript">
        var input = $('.input-a');
        input.clockpicker({
            autoclose: true
        });

        var input = $('.input-b');
        input.clockpicker({
            autoclose: true
        });
    </script>

    <!-- Optionally, you can add Slimscroll and FastClick plugins.
        Both of these plugins are recommended to enhance the
        user experience. Slimscroll is required when using the
        fixed layout. -->
{{--</body>--}}
<!--<div class='row'>
                {{--@yield('content')--}}
        </div>-->
</div>
</body>
</html>
