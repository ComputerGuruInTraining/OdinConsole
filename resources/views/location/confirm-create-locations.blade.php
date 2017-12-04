@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Confirm Location
@stop

@section('page-content')

    <div class='form-pages'>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="alert alert-warning alert-custom">
        <strong>Important!</strong> Please confirm the location data is correct
        </div>

        {{ Form::open(['role' => 'form', 'url' => '/location-created']) }}

        @include('map-location-confirm')

        <div class='form-group padding-top'>
            {{ Form::label('name', 'Address Alias') }}
            {{ Form::text('name', $alias, ['class' => 'form-control', 'disabled' => 'disabled']) }}
        </div>

        <div class='form-group'>
            {{ Form::label('info', 'Location Notes') }}
            {{ Form::text('info', $notes, ['class' => 'form-control', 'disabled' => 'disabled']) }}
        </div>

        <div class='form-group form-buttons'>
            {{--todo: with input for the back btn--}}
            {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
{{--            {{ Form::cancel('Back', ['class' => 'btn btn-primary', 'onkeypress'=>'return noenter()']) }}--}}

            <a href="/location-back" class="btn btn-info" style="margin-right: 3px;">Back</a>
        </div>
        {{ Form::close() }}
    </div>
@stop

