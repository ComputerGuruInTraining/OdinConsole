@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Report Detail
@stop

@section('page-content')
    <div class="col-md-12">
        <div class="col-md-6">


            <table class="table">
                @foreach($cases as $case)
                <tr>
                    <th>
                        Case Title:
                    </th>

                    <td>
                        {{$case->id}}
                    </td>

                </tr>
                <tr>
                    <th>
                        Last Name:
                    </th>
                    <td>
                        {{$case->title}}
                    </td>
                </tr>
                <tr>
                    <th>
                        Date of Birth:
                    </th>
                    <td>
                        {{$case->report_case_id}}
                    </td>
                </tr>
                <tr>
                    <th>
                        Gender:
                    </th>
                    <td>
                        {{$case->description}}
                    </td>
                </tr>

                {{--<tr>--}}
                    {{--<th>--}}
                        {{--Mobile:--}}
                    {{--</th>--}}
                    {{--<td>--}}
                        {{--{{$employee->mobile}}--}}
                    {{--</td>--}}
                {{--</tr>--}}
                {{--<tr>--}}
                    {{--<th>--}}
                        {{--Email:--}}
                    {{--</th>--}}
                    {{--<td>--}}
                        {{--{{$employee->email}}--}}
                    {{--</td>--}}
                {{--</tr>--}}
                {{--<tr>--}}
                    {{--<th>--}}
                        {{--Address:--}}
                    {{--</th>--}}
                    {{--<td>--}}
                        {{--{{$employee->address}}--}}
                    {{--</td>--}}
                {{--</tr>--}}
                @endforeach
            </table>


            {{--<div class="form-group form-buttons">--}}
                {{--{{ Form::open(array('route' => array('employees.destroy', $employee), 'method' => 'delete')) }}--}}
                {{--<a href="{{ $employee->id }}/edit" class="btn btn-success">Edit</a>--}}
                {{--<button type="submit" href="{{ URL::route('employees.destroy', $employee) }}" class="btn btn-danger btn-mini" onclick="if(!confirm('Are you sure delete this record?')){return false;};">Delete</button>--}}
                {{--{{ Form::close() }}--}}

                {{--TODO show deletion successfull message--}}
                {{--not working--}}
                {{--@if(Session::has('flash_message'))--}}
                    {{--<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {{ session('flash_message') }}</em></div>--}}
                {{--@endif--}}


            {{--</div>--}}

        </div>
    </div>
@stop
