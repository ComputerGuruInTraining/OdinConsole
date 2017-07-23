@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Edit Employee Details
@stop

@section('page-content')
<div class='col-md-8'>


{{-- TODO Form Validation --}}

  {{--Dont know if its a good way to show it as in a forloop--}}
    @foreach($employees as $employee)
{{ Form::model($employee, ['route' => ['employees.update', $employee->user_id], 'method'=>'put']) }}
<div class='form-group'>
    {{ Form::label('first_name', 'First Name') }}
    {{ Form::text('first_name', $employee->first_name, ['placeholder' => 'First Name', 'class' => 'form-control']) }}
</div>

<div class='form-group'>
    {{ Form::label('last_name', 'Last Name') }}
    {{ Form::text('last_name', $employee->last_name, ['placeholder' => 'Last Name', 'class' => 'form-control']) }}
</div>

<div class='form-group'>
    {{ Form::label('dateOfBirth', 'Date of Birth') }}
    {{ Form::text('dateOfBirth', $employee->dob, array('class' => 'datepicker')) }}



    {{--{{ Form::text('date', $employee->dob, array('id' => 'datepicker')) }}--}}
    {{--{{ Form::text('date',null, ['class'=>'form-control', 'placeholder'=>$employee->dob, 'class' =>'datepicker']) }}--}}
    
</div>


<div class='form-group'>
  {{ Form::label('male', 'Male') }}
  {{ Form::radio('sex', 'M', ($employee->gender == 'M')) }}
  {{ Form::label('female', 'Female') }}
  {{ Form::radio('sex', 'F', ($employee->gender == 'F')) }}
</div>

<div class='form-group'>
  {{ Form::label('mobile','Mobile')}}
  {{ Form::number('mobile', $employee->mobile, ['placeholder' => 'Mobile', 'class' => 'form-control']) }}

</div>

<div class='form-group'>
    {{ Form::label('email', 'Email') }}
    {{ Form::email('email', $employee->email, ['placeholder' => 'Email', 'class' => 'form-control']) }}

</div>

    {{--TODO: validate old password--}}
    <div class='form-group'>
            {{ Form::label('old_password', 'Old Password') }}
            {{ Form::password('old_password',['placeholder' => 'Password', 'class' => 'form-control']) }}
    </div>

<div class='form-group'>
    {{ Form::label('new_password', 'Password') }}
    {{ Form::password('password',['placeholder' => 'Password', 'class' => 'form-control']) }}
</div>

<div class='form-group'>
    {{ Form::label('password_confirmation', 'Confirm Password') }}
    {{ Form::password('password_confirmation', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) }}
</div>
<div class='form-group'>
    {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
    <a href ='/employees' class = "btn btn-info">Back</a>
</div>
    @endforeach


{{ Form::close() }}
</div>
@stop
