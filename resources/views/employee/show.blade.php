@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Employee Detail
@stop

@section('page-content')
<div class="col-md-12">
<div class="col-md-6">


<table class="table">
  <tr>
    <th>
      First Name:
    </th>
    <td>
      {{$employee->first_name}}
    </td>
  </tr>
  <tr>
    <th>
      Last Name:
    </th>
    <td>
      {{$employee->last_name}}
    </td>
  </tr>
  <tr>
    <th>
      Date of Birth:
    </th>
    <td>
      {{$employee->dob}}
    </td>
  </tr>
  <tr>
    <th>
      Gender:
    </th>
    <td>
      {{$employee->gender}}
    </td>
  </tr>

  <tr>
    <th>
      Mobile:
    </th>
    <td>
      {{$employee->mobile}}
    </td>
  </tr>
  <tr>
    <th>
      Email:
    </th>
    <td>
      {{$employee->email}}
    </td>
  </tr>
  <tr>
    <th>
      Address:
    </th>
    <td>
      {{$employee->address}}
    </td>
  </tr>

</table>


<div class="form-group form-buttons">
  {{ Form::open(array('route' => array('employees.destroy', $employee), 'method' => 'delete')) }}
    <a href="{{ $employee->id }}/edit" class="btn btn-success">Edit</a>
    <button type="submit" href="{{ URL::route('employees.destroy', $employee) }}" class="btn btn-danger btn-mini" onclick="if(!confirm('Are you sure delete this record?')){return false;};">Delete</button>
  {{ Form::close() }}

  {{--TODO show deletion successfull message--}}
  {{--not working--}}
  @if(Session::has('flash_message'))
    <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {{ session('flash_message') }}</em></div>
  @endif


</div>

</div>
</div>
@stop
