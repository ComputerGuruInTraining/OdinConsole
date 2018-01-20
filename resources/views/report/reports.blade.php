@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Reports
@stop

@section('page-content')
    <div class="col-md-12">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="padding:15px 0px 10px 0px;">
            <button type="button" class="btn btn-success" onclick="window.location.href='reports/create'">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Generate Report
            </button>
        </div>

        <div class='table-responsive padding-top'>
            <table class="table table-hover">
                <tr>
                    <th>Date Range</th>
                    <th>Activity</th>
                    {{--Subject, Regarding, Reporting About, RE, --}}
                    <th>Entity</th>
                    <th>Manage</th>
                </tr>
                {{--check to ensure at least one report exists first, otherwise view error thrown--}}
                @if(count($reports) > 0)
                    {{--check to ensure have more than just a bit of info and relevant report data was generated--}}
                    @foreach($reports as $report)
                        @if(($report->location != "")||($report->individual != ""))
                            <tbody class='group-list'>
                            <tr>
                                <td>{{$report->form_start}} - {{$report->form_end}}</td>

                                <td>{{$report->type}}</td>

                                {{--Report Entity--}}
                                @if($report->location != "")
                                    <td>{{$report->location}}</td>
                                @elseif($report->individual != "")
                                    <td>{{$report->individual}}</td>
                                @else
                                    <td></td>
                                @endif


                                <td><a href="report-{{$report->id}}">View Report</a> |
                                    <a href="{{ route('pdf',['id' => $report->id, 'download'=>'pdf']) }}"
                                       class="edit-links" target="_blank">Download PDF</a>
                                    | <a href="/confirm-delete/{{$report->id}}/{{$url}}" style="color: #990000;">Delete
                                        Report</a>
                                    {{--| <a href="/reports/{{$report->id}}/edit" class="edit-links">Edit or Delete Case Notes</a>--}}
                                </td>
                            </tr>
                            </tbody>
                        @endif
                    @endforeach
                @endif
            </table>
        </div>

    </div>
@stop