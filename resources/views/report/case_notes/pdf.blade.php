<html>
<head>
    <title>Report PDF</title>

    <style>
        .report-title {
            color: #663974;
        }

        #report-heading{
            font-family: papyrus, courier, georgia;
            font-size: x-large;
        }

        .report-header{
            padding-left: 15px;
            color: #000;
            font-size: large;
        }

        #report-date{
            color: #777;
        }

        .report-header-row{
            line-height: 1.5;
            vertical-align: top;
        }

        /*#inline{*/
            /*display: inline-block;*/
        /*}*/

        .margin-bottom {
            margin-bottom: 20px;
        }

        /*Fonts Large*/
        .table > tbody > tr > td,
        .table >tbody >tr > th,
        body
        {
            font-size: large !important;
            text-align: left;
        }

        .table > tbody > tr > th,
        .table > tbody > tr > td{
            padding: 5px;

        }

        .table > tbody > tr > th{
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
            <link rel="stylesheet" href="{{ asset("/bower_components/adminlte/bootstrap/css/bootstrap.min.css") }}" type="text/css">
        */

        .col-md-12  {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }

        .table-responsive {
            overflow: auto;
        }

        .table-responsive > .table tr th,
        .table-responsive > .table tr td {
            white-space: normal !important;
        }

        .table-responsive {
            min-height: .01%;
            overflow-x: auto;
        }
        @media screen and (max-width: 767px) {
            .table-responsive {
                width: 100%;
                margin-bottom: 15px;
                overflow-y: hidden;
                -ms-overflow-style: -ms-autohiding-scrollbar;
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
        <div>
            <div class="table-responsive">
                <div>
                    <h3 class="report-title" id="report-heading">{{$report->type}} Report</h3>

                    <a href="{{ route('pdf',['id' => $report->id, 'download'=>'pdf']) }}">Download PDF</a>

                </div>
                <table class="col-md-12 margin-bottom">
                    <tr><h4 id="report-date">{{$start}} - {{$end}}</h4></tr>
                    <tr class="report-header-row"><td>Premise:</td></td><td class="report-header">{{$cases->location->address}}</td></tr>
                    <tr class="report-header-row"><td>Hours Monitoring Premise:</td><td class="report-header"> {{$cases->reportCases->total_hours}}</td></tr>
                    <tr class="report-header-row"><td>Guard Presence at Location:</td><td class="report-header">{{$cases->reportCases->total_guards}}</td></tr>
                </table>

                <table class="table">
                    {{--if there are case notes to report--}}
                    <tr>
                        {{--<th>Premise</th>--}}
                        <th>Date</th>
                        <th>Time</th>
                        <th>Case Title</th>
                        <th>Case Description</th>
                        <th>Case Image</th>
                        <th>Reporting Guard</th>
                        <th>Case Id</th>
                    </tr>
                    {{--Check to ensure there are case notes or else an error will be thrown--}}
                    @if(count($cases->reportCaseNotes) != 0)

                        @foreach($groupCases as $index => $note)
                            <tbody class="group-list">

                            <tr>
                                <td class="report-title">{{$index}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @foreach ($groupCases->get($index) as $item)
                                <tr>
                                    <td></td>
                                    <td>{{$item->case_time}}</td>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->description}}</td>
                                    <td>{{$item->img}}</td>
                                    <td>{{$item->employee}}</td>
                                    <td>{{$item->case_id}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        @endforeach

                    @else
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                    @endif
                </table>
            </div>
        </div>
    </div>
</body>
</html>
