{{--TODO: change to extend a create_layout once layout exists--}}
@extends('layouts.master_layout')
@extends('sidebar')

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

        {{ Form::model($location, ['route' => ['location.update', $location->id], 'method' => 'put']) }}

        <div class='form-group'>
            {{ Form::label('name', 'Address alias') }}
            {{ Form::text('name', $location->name) }}
        </div>

        <div class='form-group'>
            {{ Form::label('address', 'Address') }}
            {{ Form::text('address', $location->address) }}
        </div>

        <div class='form-group'>
            {{ Form::label('info', 'Additional Address Details') }}
            {{ Form::text('info', null, ['placeholder' => 'eg Building 25', 'class' => 'form-control']) }}
        </div>

        <div class='form-group form-buttons'>
            {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
            {{--TODO: cancel btn code, see button attributes allowed--}}
            {{ Form::button('Cancel', ['class' => 'btn btn-primary']) }}
        </div>

        {{ Form::close() }}

    </div>
@stop

