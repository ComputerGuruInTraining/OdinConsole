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

            #grey-color-app{
                color: #555;
            }

            .row>.title-bg-app>.title-block-app{
                background-color: #f5f5f5 !important;
                height: 150px;
                margin-top: 50px;
                width: 100%;
            }

            .title-block-app{
                padding-top: 50px;
                text-align: center;
            }

            .title-content-app > .title-heading-app{
                font-size: 20px !important;
            }

            .subtitle-app{
                font-size: x-large;
                text-align: center;
            }

            .title-app{
                font-size: 3.8rem;
            }

            .title-content-app{
                margin: 20px 200px;
                line-height: 3.0rem;
                text-align: justify;
            }

            .title-content-alt-app {
                height: 550px;
                margin: 20px 500px !important;
            }

            .title-content-app > p, .title-content-app > ul{
                font-size: 16px;
            }

            .text-bold{
                font-weight: 600;

            }

            .title-text-app{
                font-size: 20px;

            }
        </style>

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
