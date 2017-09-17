<html>
<head>
    <title>Report PDF</title>
    <link rel='stylesheet' href='//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css'>

{{--    <link rel="stylesheet" href="{{base_path("/public/bower_components/AdminLTE/dist/css/AdminLTE.css")}}" type="text/css">--}}
    <link rel="stylesheet" href="{{ asset("/bower_components/adminlte/dist/css/AdminLTE.css")}}" type="text/css">


    {{--<style>--}}
        {{--.report-title {--}}
            {{--color: #663974;--}}
        {{--}--}}

        {{--#report-heading{--}}
            {{--font-family: papyrus, courier, georgia;--}}
            {{--font-size: xx-large;--}}
        {{--}--}}

        {{--.report-header{--}}
            {{--padding-left: 15px;--}}
            {{--color: #000;--}}
            {{--font-size: larger;--}}
        {{--}--}}

        {{--#report-date{--}}
            {{--color: #777;--}}
        {{--}--}}

        {{--.report-header-row{--}}

            {{--line-height: 1.5;--}}
            {{--vertical-align: top;--}}
        {{--}--}}

    {{--</style>--}}

</head>
<body>
    <div class="col-md-12">
        <div>
            <div class='table-responsive'>
                <div id="inline">
                    <h3 class="report-title" id="report-heading">{{$report->type}} Report</h3>

                    <a href="{{ route('pdf',['id' => $report->id, 'download'=>'pdf']) }}">Download PDF</a>

                </div>
                <table class="col-md-12 margin-bottom">
                    <tr><h4 id="report-date">{{$start}} - {{$end}}</h4></tr>
                    <tr class="report-header-row"><td>Premise:</td></td><td class="report-header">{{$cases->location->address}}</td></tr>
                    <tr class="report-header-row"><td>Hours Monitoring Premise:</td><td class="report-header"> {{$cases->reportCases->total_hours}}</td></tr>
                    <tr class="report-header-row"><td>Guard Presence at Location:</td><td class="report-header">{{$cases->reportCases->total_guards}}</td></tr>
                </table>

                <table class="table table-hover">
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
