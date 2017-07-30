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

        <div class='table-responsive col-md-10 padding-top'>
            <table class="table table-hover">
                <tr>
                    <th>Report Type</th>
                    <th>Date Range</th>
                    <th>Actions</th>
                </tr>
                @foreach($reports as $report)
                    <tbody class='group-list'>
                    <tr>
                        <td>{{$report->type}}</td><!--TODO: make case notes a variable in code-->
                        <td>{{$report->form_start}} - {{$report->form_end}}</td>
                        <td>
                            <a href="/reports/{{$report->id}}">View Report</a> | <a href="/confirm-delete/{{$report->id}}/{{$url}}" style="color: #990000;">Delete Report</a> | <a href="/reports/{{$report->id}}/edit" class="edit-links">Edit or Delete Case Notes</a>
                        </td>
                    </tr>
                    </tbody>
                @endforeach
            </table>
        </div>

    </div>
@stop