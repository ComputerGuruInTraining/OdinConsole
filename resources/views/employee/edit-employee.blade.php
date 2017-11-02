@extends('layouts.master_layout_dob')
@extends('sidebar')

@section('title-item')
    Edit Employee Details
@stop

@section('page-content')
<div class='col-md-4 form-pages'>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        {{--{{ Form::open(['route' => ['rosters.update', $assigned[0]->assigned_shift_id], 'method'=>'put']) }}--}}

        {{--{{ Form::open(['route' => ['employeeupdate', $employee->user_id], 'method'=>'put']) }}--}}

        {{--{{ Form::model($employee, ['url' => '/employee-updated-' . $employee->user_id, 'method' => 'put']) }}--}}
        {{--{{ Form::open(['route' => ['rosters.update', $assigned[0]->assigned_shift_id], 'method'=>'put']) }}--}}

        {{ Form::open(['route' => ['employees.update', $employee->user_id], 'method'=>'put']) }}
    {{--{{ Form::model($employee, ['route' => ['employees.update', $employee->user_id], 'method'=>'put']) }}--}}
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
        {{ Form::text('dateOfBirth', '', array('class' => 'datepicker',  'onkeypress'=>'return noenter()')) }}
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
    {{--<div class='form-group'>--}}
            {{--{{ Form::label('old_password', 'Old Password') }}--}}
            {{--{{ Form::password('old_password',['placeholder' => 'Password', 'class' => 'form-control']) }}--}}
    {{--</div>--}}

{{--<div class='form-group'>--}}
    {{--{{ Form::label('new_password', 'Password') }}--}}
    {{--{{ Form::password('password',['placeholder' => 'Password', 'class' => 'form-control']) }}--}}
{{--</div>--}}

{{--<div class='form-group'>--}}
    {{--{{ Form::label('password_confirmation', 'Confirm Password') }}--}}
    {{--{{ Form::password('password_confirmation', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) }}--}}
{{--</div>--}}
<div class='form-group form-buttons'>
    {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
    <a href ='/employees' class = "btn btn-info">Cancel</a>
</div>


{{ Form::close() }}
</div>
@stop
