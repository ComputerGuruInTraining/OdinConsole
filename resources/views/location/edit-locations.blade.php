{{--TODO: change to extend a create_layout once layout exists--}}
@extends('layouts.master_layout')
@include('sidebar')

@section('title-item')
    Edit Location
@stop

@section('page-content')
    <div class='col-lg-4 col-lg-offset-4 form-pages'>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ Form::model($location, ['route' => ['locations.update', $location->id], 'method' => 'put']) }}

            @include('map')

        <div class='form-group padding-top'>
            {{ Form::label('name', 'Address alias') }}
            {{ Form::text('name', $location->name, ['onkeypress'=>'return noenter()']) }}
        </div>

        <div class='form-group'>
            {{ Form::label('info', 'Additional Address Details') }}
            {{ Form::text('info', $location->additional_info, ['placeholder' => 'eg Building 25', 'class' => 'form-control', 'onkeypress'=>'return noenter()']) }}
        </div>

        <div class='form-group form-buttons'>
            {{ Form::submit('Save', ['class' => 'btn btn-info']) }}
            <a href="/location" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        </div>

        {{ Form::close() }}

    </div>
@stop

