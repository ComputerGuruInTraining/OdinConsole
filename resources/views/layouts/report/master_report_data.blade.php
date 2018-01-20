@extends('layouts.master_layout')
@extends('sidebar')

@section('page-content')
    <div class="col-md-12">
        <div class='table-responsive'>

            <div style="padding:15px 0px 10px 0px;">
                @yield('button')
                <a href="{{ route('pdf',['id' => $report->id, 'download'=>'pdf']) }}" class="btn btn-primary" target="_blank">Download
                    PDF</a>
            </div>

            <table class="col-md-12 margin-bottom report-table">
                <tr><td class="col-md-3">{{$start}} - {{$end}}</td><td></td></tr>
                <tr><td class="col-md-3">Activity Report:</td>
                    <td class="col-md-3">@yield('entity-value-show')</td>
                </tr>
                <tr>
                    <td class="col-md-3">@yield('total1-show')</td>
                    <td class="col-md-3">@yield('total1-val-show')</td>
                </tr>
                <tr>
                    <td class="col-md-3">@yield('total2-show')</td>
                    <td class="col-md-3">@yield('total2-val-show')</td>
                </tr>

            </table>

            <table class="table table-hover bottom-border">
                <tr>
                    <th>@yield('colHeading1-show')</th>
                    <th class="min-width">@yield('colHeading2-show')</th>
                    <th class="min-width">@yield('colHeading3-show')</th>
                    <th>@yield('colHeading4-show')</th>
                    <th>@yield('colHeading5-show')</th>
                    <th class="min-width">@yield('colHeading6-show')</th>
                    <th>@yield('colHeading7-show')</th>
                    <th>@yield('colHeading8-show')</th>
                </tr>

                @yield('report-content-show')

            </table>
        </div>
    </div>
    <br/>
    <br/>

    @yield('add1-report-content-show')

@stop

