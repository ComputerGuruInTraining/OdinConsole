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
                    <td>Number of Checks at Premise:</td>
                    <td class="report-header grey-larger"> {{$total}}</td>
                </tr>
                <tr class="report-header-row">
                    <td>Total Hours Monitoring Premise:</td>
                    <td class="report-header">{{$report->totalHours}}</td>
                </tr>
            </table>

            <table class="table table-hover bottom-border">
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
{{--
                --}}{{--Check to ensure there are case notes or else an error will be thrown--}}{{--
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

                                --}}{{--action--}}{{--
                                @if($item->title == "Nothing to Report")
                                    <td>Nothing to Report</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @else
                                    <td>Case Note Reported</td>
                                    <td># {{$item->case_id}}</td>
                                    <td>{{$item->title}}</td>

                                    --}}{{--description--}}{{--
                                    @if(isset($item->shortDesc))
                                        <td>{{$item->shortDesc}}</td>
                                    @else
                                        <td>{{$item->description}}</td>
                                    @endif
                                @endif

                                --}}{{--Image--}}{{--

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
                @endif--}}
                    @include('report.client.shared')
            </table>
        </div>
    </div>
    <br/>
    <br/>
    @include('report.client.shared_details')


    {{--<div class="col-md-12">
        <p class="details-heading">Full Details</p>
        @if(count($data) != 0)
            @foreach($data as $index => $shiftCheck)
                @foreach ($data->get($index) as $item)
                    @if($item->title != "Nothing to Report")
                        <div class="padding-top content-app top-border">
                            <p>
                                <span class="col-md-3">Case ID:</span>
                                <span class="col-md-9"># {{$item->case_id}}</span>
                            </p>
                            <br/>
                            <p>
                                <span class="col-md-3">
                                Total Check In Time:
                                </span>
                                --}}{{--todo: minutes and seconds--}}{{--
                                <span class="col-md-9">
                                            @if(isset($item->checkDuration))
                                        @if($item->checkDuration < 1)
                                            --}}{{--<span class="col-md-6">--}}{{--
                                            < 1 min
                                            --}}{{--</span>--}}{{--
                                        @else
                                            --}}{{--<span class="col-md-6">--}}{{--
                                            {{$item->checkDuration}} min/s
                                            --}}{{--</span>--}}{{--
                                        @endif
                                    @else
                                        Insufficient Data
                                    @endif
                                            </span>
                            </p>
                            <br/>
                            <p>
                                <span class="col-md-3">Case Note Title:</span>
                                <span class="col-md-9">{{$item->title}}</span>
                            </p>
                            <br/>
                            --}}{{--description--}}{{--
                            --}}{{--todo : loop through once add the ability to add more case notes for a case id/title--}}{{--
                            @if($item->description != null)
                                <p>
                                    <span class="col-md-3">Case Notes:</span>
                                    <span class="col-md-9">{{$item->description}}</span>
                                </p>
                                <br/>
                            @endif
                            --}}{{--Images--}}{{--
                            @if(isset($item->files))
                                @if(sizeof($item->files) > 0)

                                    <span class="col-md-3">Images: (WIP)</span>
                                    <br/>
                                    @for($i=0; $i < sizeof($item->files); $i++)

                                        <a class="col-md-offset-9" href="{{$item->urls[$i]}}" target="_blank">
                                            Download Image {{$i + 1}}
                                        </a>
                                        <br/>
                                    @endfor
                                @endif
                            @endif
                        </div>
                    @endif
                @endforeach
            @endforeach
        @endif
    </div>--}}
@stop

