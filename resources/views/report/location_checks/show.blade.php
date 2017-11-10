@extends('layouts.master_layout')
@extends('sidebar')

{{--@section('title-item')--}}

{{--@stop--}}

@section('page-content')
    <div class="col-md-12">
        <div>
            <div class='table-responsive'>
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
                        <th>Case Id</th>
                        <th>Case Title</th>
                        <th>Security Guard</th>
                        <th class="max-width">Geo - Located Within 200m</th>
                    </tr>
                    {{--Check to ensure there are case notes or else an error will be thrown--}}
                    @if(count($shiftChecks) != 0)
                        @foreach($shiftChecks as $index => $shiftCheck)

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

                            @foreach ($shiftChecks->get($index) as $item)

                                <tr>
                                    <td></td>
                                    <td>{{$item->timeTzCheckIn}}</td>
                                    <td>{{$item->timeTzCheckOut}}</td>
                                    <td>{{$item->case_id}}</td>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->user}}</td>

                                    @if($item->withinRange == 'yes')
                                        <td><i class="fa fa-check green-tick" aria-hidden="true"></i></td>
                                    @elseif($item->withinRange == 'ok')
                                        <td><i class="fa fa-check orange-tick" aria-hidden="true"></i></td>
                                    @elseif($item->withinRange == 'no')
                                        <td><i class="fa fa-times red-cross" aria-hidden="true"></i></td>
                                    @else
                                        <td><i class="fa fa-minus" aria-hidden="true"></i></td>
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
                    @endif
                </table>
            </div>
        </div>
    </div>
@stop
