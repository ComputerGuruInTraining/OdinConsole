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
    <link rel="stylesheet" href="{{ asset("/bower_components/adminlte/bootstrap/css/bootstrap.min.css") }}"
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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">

    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <script type="text/javascript"
            src="{{asset('/bower_components/adminlte/plugins/jQuery/jquery-3.2.1.js')}}"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="hold-transition">
<div class='container-fluid'>
    <!-- display/hide loading on page navigate/load-->
    {{--<script>--}}
        {{--window.addEventListener("load", function () {--}}

            {{--var loaderAlt = document.getElementsByClassName('loader-alt-header')[0];--}}
            {{--loaderAlt.style.display = "none";--}}
        {{--});--}}

        {{--function displayLoaderAlt(){--}}

            {{--var loaderAlt = document.getElementsByClassName('loader-alt-header')[0];--}}
            {{--loaderAlt.style.display = "block";--}}
        {{--}--}}

    {{--</script>--}}
    <div class="wrapper">
    <!-- Main Header
            <header class="main-header">  -->
    @include('header_alt')

    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper content-wrapper-alt">

            <section class="content-header">
                <h1 class="master-alt">
                @yield('title-item')
                </h1>
            </section>
            {{--<div class="loader-alt-header"></div>--}}
            @yield('page-content')
        </div>
        <!-- /.content-wrapper -->
    </div>
    <!-- ./wrapper -->
    <!-- Footer -->
    @include('footer_alt')

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
