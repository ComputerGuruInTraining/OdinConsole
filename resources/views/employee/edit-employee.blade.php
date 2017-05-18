@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Edit Employee Details
@stop

@section('page-content')
<div class='col-md-8'>


{{-- TODO Form Validation --}}
{{ Form::model($employee, ['route' => ['employees.update', $employee->id], 'method'=>'put']) }}
<div class='form-group'>
    {{ Form::label('first_name', 'First Name') }}
    {{ Form::text('first_name', null, ['placeholder' => 'First Name', 'class' => 'form-control']) }}
</div>

<div class='form-group'>
    {{ Form::label('last_name', 'Last Name') }}
    {{ Form::text('last_name', null, ['placeholder' => 'Last Name', 'class' => 'form-control']) }}
</div>

<div class='form-group'>
    {{ Form::label('dob', 'Date of Birth') }}
    {{--{{ Form::text('date', 'dob', $employee->dob, ['id'=>'datepicker']) }}--}}

    {{--{{ Form::text('date', $employee->dob, array('id' => 'datepicker')) }}--}}
    {{ Form::text('date',null, ['class'=>'form-control', 'placeholder'=>$employee->do
    b, 'class' =>'datepicker']) }}
    
</div>


<div class='form-group'>
  {{ Form::label('male', 'Male') }}
  {{ Form::radio('sex', 'M', ($employee->gender == 'M')) }}
  {{ Form::label('female', 'Female') }}
  {{ Form::radio('sex', 'F', ($employee->gender == 'F')) }}
</div>

<div class='form-group'>
  {{ Form::label('mobile','Mobile')}}
  {{ Form::number('mobile', null, ['placeholder' => 'Mobile', 'class' => 'form-control']) }}

</div>

<div class='form-group'>
    {{ Form::label('email', 'Email') }}
    {{ Form::email('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) }}

</div>

<div class='form-group'>
    {{ Form::label('password', 'Password') }}
    {{ Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) }}
</div>

<div class='form-group'>
    {{ Form::label('password_confirmation', 'Confirm Password') }}
    {{ Form::password('password_confirmation', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) }}
</div>
<div class='form-group'>
    {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
    <a href ='/employees' class = "btn btn-info">Back</a>
</div>


{{ Form::close() }}
</div>
@stop
