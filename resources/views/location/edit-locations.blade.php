{{--TODO: change to extend a create_layout once layout exists--}}
@extends('layouts.master_layout')
@include('sidebar')

@section('title-item')
    Edit Location
@stop

@section('page-content')
    <div class="form-pages">
        @if (count($errors) > 0)web
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ Form::model($location, ['url' => '/location-edit-confirm-' . $location->id, 'method' => 'put']) }}

            @include('map')

        <div class='form-group padding-top'>
            {{ Form::label('name', 'Address alias') }}
            {{ Form::text('name', $location->name, ['class' => 'form-control', 'onkeypress'=>'return noenter()']) }}
        </div>

        <div class='form-group padding-top'>
            {{ Form::label('notes', 'Location Notes') }}
            {{ Form::text('notes', $location->notes, ['placeholder' => 'ie instructions that always apply to the location, or building name, company name, etc.', 'class' => 'form-control', 'onkeypress'=>'return noenter()']) }}
        </div>

        <div class='form-group form-buttons'>
            {{ Form::submit('Next', ['class' => 'btn btn-primary']) }}
            <a href="/location" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        </div>

        {{ Form::close() }}

    </div>
@stop

