<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>@yield('title') | User Admin</title>

        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset("/bower_components/adminlte/dist/css/AdminLTE.css")}}" type="text/css">

        <link rel='stylesheet' href='//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css'>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">

        <style>
            body {
                margin-top: 5%;
            }
        </style>

        {{--@yield('my-styles')--}}
    </head>
    <body>

        <div class='container-fluid'>
            <div class='row'>
                @yield('content')
            </div>
        </div>
        <div>
            @include('footer_alt')
        </div>
    </body>
</html>
