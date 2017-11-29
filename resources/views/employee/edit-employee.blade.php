@extends('layouts.master_layout_dob')
@extends('sidebar')

@section('title-item')
    Edit Employee Details
@stop

@section('page-content')
<div class='col-md-8 form-pages'>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{ Form::open(['route' => ['employees.update', $employee->user_id], 'method'=>'put']) }}

    <div class='form-group'>
        {{ Form::label('first_name', 'First Name *') }}
        {{ Form::text('first_name', $employee->first_name, ['placeholder' => 'First Name', 'class' => 'form-control']) }}
    </div>

    <div class='form-group'>
        {{ Form::label('last_name', 'Last Name *') }}
        {{ Form::text('last_name', $employee->last_name, ['placeholder' => 'Last Name', 'class' => 'form-control']) }}
    </div>

    <div class='form-group'>
        {{ Form::label('dateOfBirth', 'Date of Birth *') }}
        {{ Form::text('dateOfBirth', $dateBirth, array('class' => 'datepicker',  'onkeypress'=>'return noenter()')) }}
    </div>

    <div class='form-group'>
        *
      {{ Form::label('male', 'Male') }}
      {{ Form::radio('sex', 'M', ($employee->gender == 'M')) }}
      {{ Form::label('female', 'Female') }}
      {{ Form::radio('sex', 'F', ($employee->gender == 'F')) }}
    </div>

    <div class='form-group'>
      {{ Form::label('mobile','Mobile *')}}
      {{ Form::text('mobile', $employee->mobile, ['placeholder' => 'Mobile', 'class' => 'form-control']) }}

    </div>

    <div class='form-group'>
        {{ Form::label('email', 'Email *') }}
        {{ Form::email('email', $employee->email, ['placeholder' => 'Email', 'class' => 'form-control']) }}

    </div>

<div class='form-group form-buttons'>
    {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
    <a href ='/employees' class = "btn btn-info">Cancel</a>
</div>

{{ Form::close() }}
</div>
@stop
