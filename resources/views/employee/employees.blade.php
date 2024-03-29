@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Employees
@stop

@section('page-content')
    <div class="col-md-12">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="active" style="padding:15px 0px 10px 0px;">
            <button type="button" class="btn btn-success menu" onclick="window.location.href='employees/create'">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Employee
            </button>

            <button type="button" class="btn btn-success menu" onclick="window.location.href='employee/create-existing'">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Existing User as Employee
            </button>
        </div>

        <div class='table-responsive'>
            <table class="table  table-hover">
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th class="col-min-width-sm">Manage</th>
                </tr>

                @foreach($employees as $employee)

                    <tbody class='group-list'><tr>

                        <td>{{$employee->first_name}}</td>
                        <td>{{$employee->last_name}}</td>
                        <td>{{$employee->dateBirth}}</td>
                        <td>{{$employee->gender}}</td>
                        <td>{{$employee->mobile}}</td>
                        <td>{{$employee->email}}</td>

                        <td>
                            <a href="/employees/{{$employee->user_id}}/edit"><i class="fa fa-edit"></i></a>
                            @if(session('id') != $employee->user_id)
                                <a href="/confirm-delete/{{$employee->user_id}}/{{$url}}" style="color: #990000;"><i class="fa fa-trash-o icon-padding"></i></a>
                            @else
                                <i class="fa fa-trash-o icon-padding"></i>
                            @endif
                        </td>

                    </tr></tbody>
                @endforeach

            </table>
        </div>
    </div>
@stop
