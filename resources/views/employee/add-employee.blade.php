@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Add Employee
@stop

@section('page-content')
<div class='col-md-8'>


{{-- {{ Form::open(['role' => 'form', 'url' => '/employees']) }} --}}
{{ Form:: open(['role' =>'form', 'url' =>'employees']) }}
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
  {{ Form::text('dateOfBirth', '', array('class' => 'datepicker',  'onkeypress'=>'return noenter()')) }}

    {{--{{ Form::label('startDate', 'Start Date') }}--}}
    {{--{{ Form::text('startDateTxt', '', array('class' => 'datepicker')) }}--}}

</div>


<div class='form-group'>
  {{ Form::label('male', 'Male') }}
  {{ Form::radio('sex', 'M') }}
  {{ Form::label('female', 'Female') }}
  {{ Form::radio('sex', 'F') }}
</div>
<div class='form-group'>
  {{ Form::label('mobile','Mobile')}}
  {{ Form::number('mobile', null, ['placeholder' => 'Mobile', 'class' => 'form-control']) }}

</div>

<div class='form-group'>
    {{ Form::label('email', 'Email') }}
    {{ Form::email('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) }}

</div>

{{--<div class='form-group'>--}}
    {{--{{ Form::label('password', 'Password') }}--}}
    {{--{{ Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) }}--}}
{{--</div>--}}

{{--<div class='form-group'>--}}
    {{--{{ Form::label('password_confirmation', 'Confirm Password') }}--}}
    {{--{{ Form::password('password_confirmation', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) }}--}}
{{--</div>--}}
<div class='form-group'>
    {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
</div>

{{ Form::close() }}
</div>
@stop
