{{--TODO: change to extend a create_layout once layout exists--}}
@extends('layouts.master_layout')
@extends('sidebar')


@section('my side-menu')
    Create Location
@stop

@section('title-item')
    Create Location
@stop
@section('page-content')
    <div class='col-lg-4 col-lg-offset-4 form-pages'>

        {{--@if ($errors->has())--}}
            {{--@foreach ($errors->all() as $error)--}}
                {{--<div class='bg-danger alert'>{{ $error }}</div>--}}
            {{--@endforeach--}}
        {{--@endif--}}

        {{ Form::open(['role' => 'form', 'url' => '/create-location']) }}

        <div class='form-group'>
            {{ Form::label('name', 'Address alias') }}
            {{ Form::text('name', null, ['placeholder' => 'eg Building 25 UC', 'class' => 'form-control']) }}
        </div>

        <div class='form-group'>
            {{ Form::label('address', 'Address') }}
            {{ Form::text('address', null, ['placeholder' => 'eg Building 25, University of Canberra Pantowora St, Bruce ACT', 'class' => 'form-control']) }}
        </div>

        {{--TODO: Drop-down with a list of Current Clients and a New Client option to create ones not in the list (simply create name,
        any other fields to be created at the manager's convenience depending on number of fields)--}}
        {{--<div class='form-group'>--}}
        {{--{{ Form::label('client', 'Client') }}--}}
        {{--{{ Form::text('client', null, ['placeholder' => 'Company Name', 'class' => 'form-control']) }}--}}
        {{--</div>--}}

        {{--TODO: Drop-down with a list of Areas and an option to create ones not in the list--}}
        {{--<div class='form-group'>--}}
        {{--{{ Form::label('address_group', 'Address Group') }}--}}
        {{--{{ Form::select('address_group', ['UC' => 'University of Canberra', 'Tuggeranong Shopping Centre']) }}--}}
        {{--{{ Form::text('address_group', null, ['placeholder' => 'eg University of Canberra', 'class' => 'form-control']) }}--}}
        {{--</div>--}}

        <div class='form-group form-buttons'>
            {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
            {{ Form::button('Cancel', ['class' => 'btn btn-primary']) }}
        </div>

        {{ Form::close() }}

    </div>
@stop

