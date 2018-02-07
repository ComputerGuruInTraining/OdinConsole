<html>
<head>
    <title>@yield('report-address-bar')</title>

    {{--internal stylesheet being used as external stylesheet takes much longer to load, even if only contains the same styles--}}
    <style>

        /*rotate images*/
        .rotate90 {
            -webkit-transform: rotate(90deg);
            -moz-transform: rotate(90deg);
            -o-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
            transform: rotate(90deg);
        }

        .margin-right{
            margin-right: 15px;
        }

        .margin-left-huge{
            margin-left: 200px;
        }

        .width-170{
            width: 170px !important;
            min-width: 170px !important;

        }

        .table-report{
            line-height: 1.8;
            color: #777;
            font-size: 20px;
        }

        .top-border{
            border-bottom: 2px solid #ddd;
        }

        .content-app{
            line-height: 1 !important;
            /*margin: 15px !important;*/
            text-align: justify;
        }

        .padding-top{
            padding-top: 15px !important;
        }

        .case-details{
            padding-top: 25px !important;
        }

        .details-heading{
            text-decoration: underline;
            padding-left: 5px;
        }

        .report-title {
            /*color: #4d2970;*/
            margin-left: 5px;
        }

        #report-heading {
            font-family: Futura, "Trebuchet MS", Arial, sans-serif;
            font-size: x-large;
        }

        .report-header {
            padding-left: 15px;
            font-size: large;
        }

        .grey-larger {
            color: #777;
            font-size: 18px;
        }

        #report-date {
            color: #777;
        }

        .margin-bottom {
            margin-bottom: 15px !important;
        }

        /*Fonts Large*/
        .table > tbody > tr > td,
        .table > tbody > tr > th,
        body {
            font-size: large !important;
            text-align: left;
        }

        td, tr {
            height: 18px;
        }

        .table > tbody > tr > th,
        .table > tbody > tr > td {
            padding: 8px;

        }

        .table > tbody > tr > th {
            border-top: 1px solid #f4f4f4;
            border-bottom: 1px solid #f4f4f4;

        }

        .table td {
            border-bottom: 1px solid #f4f4f4;
        }

        .table-responsive > .table tr th:last-child,
        .table-responsive > .table tr td:last-child {
            border-right: 1px solid #f4f4f4;
        }

        .table-responsive > .table tr th:first-child,
        .table-responsive > .table tr td:first-child {
            border-left: 1px solid #f4f4f4;
        }

        /*   Following styles sourced from
            <link rel="stylesheet" href="
        {{ asset("/bower_components/adminlte/bootstrap/css/bootstrap.min.css") }}
        " type="text/css">
                */

        .col-md-12 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 10px;

        }

        .table-responsive {
            overflow: auto;
            width: 100%;
        }

        .table-responsive > .table tr th,
        .table-responsive > .table tr td {
            white-space: normal !important;
        }

        .table-responsive {
            min-height: .01%;
            overflow-x: auto;
        }

        .table-report, table > tr > td.table-report {
            padding: -15px -20px -15px -10px;
        }

        @media screen and (max-width: 767px) {
            .table-responsive {
                width: 100%;
                margin-bottom: 15px;
                /*overflow-y: hidden;*/
                /*-ms-overflow-style: -ms-autohiding-scrollbar;*/
            }

            .table-responsive > .table {
                margin-bottom: 0;
            }

            .table-responsive > .table > thead > tr > th,
            .table-responsive > .table > tbody > tr > th,
            .table-responsive > .table > tfoot > tr > th,
            .table-responsive > .table > thead > tr > td,
            .table-responsive > .table > tbody > tr > td,
            .table-responsive > .table > tfoot > tr > td {
                white-space: nowrap;
            }
        }

        @media (min-width: 992px) {
            .col-md-12 {
                float: left;
            }

            .col-md-12 {
                width: 100%;
            }
        }

        /*override user agent style*/
        table {
            border-collapse: separate;
            border-spacing: 0px;
            -webkit-border-horizontal-spacing: 0px;
            -webkit-border-vertical-spacing: 0px;
        }

    </style>

</head>
<body>
<div class="col-md-12">
    <div class="table-responsive">
        {{--<div>--}}
            {{--<h3 class="report-title" id="report-heading">{{$report->type}} Report</h3>--}}
        {{--</div>--}}


        <table class="col-md-12 margin-bottom table-report">
            <tr><td class="col-md-3">{{$start}} - {{$end}}</td><td></td></tr>
            <tr><td class="col-md-3">Activity Report:</td>
                <td class="col-md-3">@yield('entity-value')</td>
            </tr>
            <tr>
                <td class="col-md-3">@yield('total1-desc')</td>
                <td class="col-md-3">@yield('total1-val')</td>
            </tr>
            <tr>
                <td class="col-md-3">@yield('total2-desc')</td>
                <td class="col-md-3">@yield('total2-val')</td>
            </tr>

        </table>

        <table class="table">
            <tr>
                <th>@yield('colHeading1')</th>
                <th>@yield('colHeading2')</th>
                <th>@yield('colHeading3')</th>
                <th>@yield('colHeading4')</th>
                <th>@yield('colHeading5')</th>
                <th>@yield('colHeading6')</th>
                <th>@yield('colHeading7')</th>
                <th>@yield('colHeading8')</th>
            </tr>

            @yield('report-content')

        </table>
    </div>
</div>
@yield('additional-report-content')
</body>
</html>
