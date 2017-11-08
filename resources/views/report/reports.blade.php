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
                    <th>Location</th>
                    <th>Report Type</th>
                    <th>Actions</th>
                </tr>
                @foreach($reports as $report)
                    <tbody class='group-list'>
                    <tr>
                        <td>{{$report->form_start}} - {{$report->form_end}}</td>
                        <td>{{$report->location}}</td>
                        <td>{{$report->type}}</td>
                        {{--<a href="pdf-{{$report->id}}">VIew PDF</a>--}}
                        <td><a href="report-{{$report->id}}">View Report</a> |
                            <a href="{{ route('pdf',['id' => $report->id, 'download'=>'pdf']) }}">Download PDF</a>
                            |  <a href="/confirm-delete/{{$report->id}}/{{$url}}" style="color: #990000;">Delete Report</a> | <a href="/reports/{{$report->id}}/edit" class="edit-links">Edit or Delete Case Notes</a>
                        </td>
                    </tr>
                    </tbody>
                @endforeach
            </table>
        </div>

    </div>
@stop