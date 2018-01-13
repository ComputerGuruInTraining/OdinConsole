@extends('layouts.master_layout')
@extends('sidebar')

{{--@section('title-item')--}}

{{--@stop--}}

@section('page-content')
    <div class="col-md-12">
        <div class='table-responsive'>

            <div style="padding:15px 0px 10px 0px;">
                <a href="{{ route('pdf',['id' => $report->id, 'download'=>'pdf']) }}" class="btn btn-primary">Download
                    PDF</a>
            </div>

            <h3 class="report-title" id="report-heading">{{$report->type}} Report</h3>
            <table class="col-md-12 margin-bottom">
                <tr><h4 id="report-date">{{$start}} - {{$end}}</h4></tr>
                <tr class="report-header-row grey-larger">
                    <td>Premise:</td>
                    </td>
                    <td class="report-header grey-larger">{{$location->address}}</td>
                </tr>
                <tr class="report-header-row grey-larger">
                    <td>Number of Check Ins:</td>
                    <td class="report-header grey-larger"> {{$total}}</td>
                </tr>
                {{--<tr class="report-header-row"><td>Guard Presence at Location:</td><td class="report-header">{{$cases->reportCases->total_guards}}</td></tr>--}}
            </table>

            <table class="table table-hover">
                <tr>
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Action</th>
                    <th>Case ID</th>
                    <th>Case Note Title</th>
                    <th>Description</th>
                    <th>Images</th>
                </tr>
                {{--Check to ensure there are case notes or else an error will be thrown--}}
                @if(count($data) != 0)
                    @foreach($data as $index => $shiftCheck)

                        <tbody class="group-list">

                        <tr>
                            <td class="report-title">{{$index}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>

                        @foreach ($data->get($index) as $item)

                            <tr>
                                <td></td>
                                <td>{{$item->timeTzCheckIn}}</td>
                                <td>{{$item->timeTzCheckOut}}</td>

                                {{--action--}}
                                @if($item->title == "Nothing to Report")
                                    <td>Nothing to Report</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @else
                                    <td>Case Note Reported</td>
                                    <td># {{$item->case_id}}</td>
                                    <td>{{$item->title}}</td>

                                    {{--description--}}
                                    @if(isset($item->shortDesc))
                                        <td>{{$item->shortDesc}}</td>
                                    @else
                                        <td>{{$item->description}}</td>
                                    @endif
                                @endif

                                {{--Image--}}

                                @if($item->hasImg == "Y")
                                    <td>Yes</td>
                                @else
                                    <td></td>
                                @endif
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
                        <td></td>
                        <td></td>
                    </tr>
                @endif
            </table>
        </div>
        <br/>
        <br/>
        <p class="details-heading">Full Details</p>

        {{--<div class='col-md-6'>--}}


                @if(count($data) != 0)
                    <div>
                        @foreach($data as $index => $shiftCheck)

                            @foreach ($data->get($index) as $item)
                                @if($item->title != "Nothing to Report")

                                    <p><span class="col-md-3">Case Id:</span><span class="col-md-3"># {{$item->case_id}}</span></p>
                                    <br/>

                                    Case Id:# {{$item->case_id}}</span></p>
                                    <br/>
                                    <p><span class="col-md-3">
                                        Total Check In Time:


                                    {{--todo: minutes and seconds--}}
                                    @if(isset($item->checkDuration))
                                        @if($item->checkDuration < 1)
                                            </span>< 1 min<span class="col-md-3">
                                            {{--<td> </td>--}}
                                        @else
                                            </span>
                                        {{$item->checkDuration}} min/s
                                        <span class="col-md-3">
                                        @endif
                                    @else
                                        </span>Insufficient Data<span class="col-md-3">
                                    @endif
                                    <p><span class="col-md-3">Case Id:</span><span class="col-md-3"># {{$item->case_id}}</span></p>
                                    <br/>

                                    <p><span class="col-md-3">Case Id:</span><span class="col-md-3"># {{$item->case_id}}</span></p>

                                    <br/>

                                <br/>


                                    {{--<table class="table">--}}
                                    {{--<tr>--}}
                                        {{--<th>--}}
                                            {{--Case ID:--}}
                                        {{--</th>--}}
                                        {{--<td>--}}
                                            {{--# {{$item->case_id}}--}}
                                        {{--</td>--}}
                                    {{--</tr>--}}
                                    {{--<tr>--}}

                                        {{--<th>--}}
                                            {{--Total Check In Time:--}}
                                        {{--</th>--}}
                                        {{--todo: minutes and seconds--}}
                                        {{--@if(isset($item->checkDuration))--}}
                                            {{--@if($item->checkDuration < 1)--}}
                                                {{--<td> < 1 min</td>--}}
                                            {{--@else--}}
                                                {{--<td>--}}
                                                    {{--{{$item->checkDuration}} min/s--}}
                                                {{--</td>--}}
                                            {{--@endif--}}
                                        {{--@else--}}
                                            {{--<td>Insufficient Data</td>--}}
                                        {{--@endif--}}

                                    {{--</tr>--}}
                                    {{--<tr>--}}
                                        {{--<th>--}}
                                            {{--Case Note Title:--}}
                                        {{--</th>--}}
                                        {{--<td>--}}
                                            {{--{{$item->title}}--}}
                                        {{--</td>--}}
                                    {{--</tr>--}}
                                    {{--<tr>--}}
                                        {{--<th>--}}
                                            {{--Case Note Description:--}}
                                        {{--</th>--}}
                                        {{--<td>--}}
                                            {{--{{$item->description}}--}}
                                        {{--</td>--}}
                                    {{--</tr>--}}

                                    {{--Image--}}
                                    {{----}}
                                    {{--for the one item,--}}
                                    {{--display the image... not sure how....--}}
                                    {{--so for the moment (find out how after complete all reports, separate to secure img download).....--}}
                                    {{----}}

                                    {{--@if(isset($item->files))--}}
                                        {{--@if(sizeof($item->files) > 0)--}}
                                            {{--@for($i=0; $i < sizeof($item->files); $i++)--}}
                                                {{--<tr>--}}
                                                    {{--<td>Image {{$i + 1}}</td>--}}
                                                {{--</tr>--}}
                                                {{--<tr>--}}
                                                    {{--<td><a href="{{$item->urls[$i]}}" target="_blank">Download--}}
                                                            {{--Image {{$i + 1}}</a></td>--}}
                                                {{--</tr>--}}
                                            {{--@endfor--}}
                                        {{--@endif--}}
                                    {{--@endif--}}
                                    {{--</table>--}}

                                @endif

                            @endforeach
                    </div>

                    @endforeach
                @endif

        </div>
    {{--</div>--}}
@stop
