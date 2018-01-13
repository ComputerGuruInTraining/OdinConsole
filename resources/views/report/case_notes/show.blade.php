@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')

@stop

@section('page-content')
    <div class="col-md-12">
        <div>
            <div class='table-responsive'>
                {{--| <a href= class="edit-links">Edit or Delete Case Notes</a>--}}
                <div style="padding:15px 0px 10px 0px;">
                    <button type="button" class="btn btn-info" onclick="window.location.href='/reports-{{$report->id}}-edit'">
                       Manage Report
                    </button>
                        <a href="{{ route('pdf',['id' => $report->id, 'download'=>'pdf']) }}" class="btn btn-primary">Download PDF</a>
                </div>

                <div id="inline">
                    <h3 class="report-title" id="report-heading">{{$report->type}} Report</h3>
                </div>
                <table class="col-md-12 margin-bottom">
                    <tr class="grey-larger"><h4 id="report-date">{{$start}} - {{$end}}</h4></tr>
                    <tr class="report-header-row grey-larger"><td>Premise:</td></td><td class="report-header grey-larger">{{$cases->location->address}}</td></tr>
                    {{--<tr class="report-header-row"><td>Hours Monitoring Premise:</td><td class="report-header"> {{$cases->reportCases->total_hours}}</td></tr>--}}
                    <tr class="report-header-row grey-larger"><td>Guard Presence at Location:</td><td class="report-header grey-larger">{{$cases->reportCases->total_guards}}</td></tr>
                 </table>

            <table class="table table-hover">
                    {{--if there are case notes to report--}}
                    <tr>
                        {{--<th>Premise</th>--}}
                        <th>Date</th>
                        {{--<th>Time</th>--}}
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
                            </tr>
                            @foreach ($groupCases->get($index) as $item)
                                <tr>
                                    <td></td>
{{--                                    <td>{{$item->case_time}}</td>--}}
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->description}}</td>
                                    @if($item->hasImg == "Y")
                                        <td><a href="{{$item->url}}" target="_blank">Download</a></td>
                                    @else
                                        <td>{{$item->hasImg}}</td>
                                    @endif
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
                            <td></td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
@stop
