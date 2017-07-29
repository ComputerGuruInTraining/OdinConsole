{{--TODO: change to extend a create_layout once layout exists--}}
@extends('layouts.master_layout')
@include('sidebar')

@section('title-item')
    Edit Location
@stop

@section('page-content')
    <div class="form-pages">
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

        <div class='form-group padding-top'>
            {{ Form::label('address', 'Address:') }}
            <p>{{$location->address}}</p>
        </div>

            @include('map')

        <div class='form-group padding-top'>
            {{ Form::label('name', 'Address alias') }}
            {{ Form::text('name', $location->name, ['class' => 'form-control', 'onkeypress'=>'return noenter()']) }}
        </div>

        <div class='form-group padding-top'>
            {{ Form::label('notes', 'Additional Address Details') }}
            {{ Form::text('notes', $location->notes, ['placeholder' => 'eg Building 25', 'class' => 'form-control', 'onkeypress'=>'return noenter()']) }}
        </div>

        <div class='form-group form-buttons'>
            {{ Form::submit('Update', ['class' => 'btn btn-info']) }}
            <a href="/locations" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        </div>

        {{ Form::close() }}

    </div>
@stop

