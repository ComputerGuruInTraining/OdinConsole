@extends('layouts.master_layout')
@extends('sidebar')

{{--@section('title-item')--}}

{{--@stop--}}

@section('page-content')
    <div class="col-md-12">
        <div>
            <div class='table-responsive'>

                <div style="padding:15px 0px 10px 0px;">
                    <a href="{{ route('pdf',['id' => $report->id, 'download'=>'pdf']) }}" class="btn btn-primary">Download PDF</a>
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
                        <th>Case Id</th>
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
                                        <td>{{$item->case_id}}</td>
                                        <td>{{$item->title}}</td>
                                        <td>{{$item->description}}</td>
                                    @endif

                                    {{--Image--}}

                                    @if($item->hasImg == "Y")
                                        @if(isset($item->files))

                                            @if(sizeof($item->files) > 0)
                                                {{--first photo in array--}}
                                                <td><a href="{{$item->urls[0]}}" target="_blank">Download Image</a></td>
                                            @else
                                                {{--v2 uploads--}}
                                                {{--todo: remove once not using v2 mobile anymore--}}
                                                <td><a href="{{$item->url}}" target="_blank">Download</a></td>
                                            @endif
                                        @else
                                            {{--v2 uploads--}}
                                            {{--todo: remove once not using v2 mobile anymore--}}
                                            <td><a href="{{$item->url}}" target="_blank">Download</a></td>

                                        @endif
                                    @else
                                        <td>{{$item->hasImg}}</td>
                                    @endif
                                </tr>

                                {{--another row for case notes which have more than 1 photo--}}
                                @if(isset($item->files))
                                    @if(sizeof($item->files) > 1)
                                        @for($i=1; $i < sizeof($item->files); $i++)
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><a href="{{$item->urls[$i]}}" target="_blank">Download Image {{$i + 1}}</a></td>
                                            </tr>
                                        @endfor
                                    @endif
                                @endif

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
        </div>
    </div>
@stop
