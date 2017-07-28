@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Employees
@stop

@section('page-content')
<div class="col-md-12">

<div style="padding:15px 0px 10px 0px;">
<button type="button" class="btn btn-success" onclick="window.location.href='employees/create'">
  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Employee
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
   {{--<th>Address</th>--}}
        <th>Actions</th>
    </tr>

  @foreach($employees as $employee)

      <tbody class='group-list'><tr>

            <td>{{$employee->first_name}}</td>
            <td>{{$employee->last_name}}</td>
            <td>{{$employee->dob}}</td>
            <td>{{$employee->gender}}</td>
            <td>{{$employee->mobile}}</td>
            <td>{{$employee->email}}</td>
            {{--<td>{{$employee->address}}</td>--}}

            <td>
                {{--<a href="/employees/{{ $employee->id }}">View</a> | <a href="/employees/{{$employee->id}}/edit">Edit</a>--}}
                <a href="/employees/{{$employee->user_id}}/edit">Edit</a> | <a href="/confirm-delete/{{$employee->user_id}}/{{$url}}" style="color: #990000;">Delete</a>

            </td>

        </tr></tbody>
@endforeach

</table>
</div>
</div>
@stop
