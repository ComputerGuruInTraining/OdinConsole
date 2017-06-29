{{--TODO: change to extend a create_layout once layout exists--}}
{{--TODO: fix sidemenu--}}
{{--TODO: include an option to Create another location so don't have to go via main page if creating a few at a time v2--}}
{{--TODO: optimize: put a suggested name in the alias field v2--}}
@extends('layouts.master_layout')
@extends('sidebar')


@section('title-item')
    Create Location
@stop

@section('page-content')

    {{--TODO: fix display in Microsoft Edge. The page content shows as approx. 1/3 of main content area--}}
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

        {{ Form::open(['role' => 'form', 'url' => '/locations']) }}
        @include('map')
        <div class='form-group padding-top'>
            {{ Form::label('name', 'Address Alias *') }}
            {{ Form::text('name', null, ['placeholder' => 'eg UC Building 25', 'class' => 'form-control', 'onkeypress'=>'return noenter()']) }}
        </div>

        <div class='form-group'>
            {{ Form::label('info', 'Additional Address Details') }}
            {{ Form::text('info', null, ['placeholder' => 'ie Building Number, Unit Number, Company Name, etc. or instructions particular to the location', 'class' => 'form-control', 'onkeypress'=>'return noenter()']) }}
        </div>

        <div class='form-group form-buttons'>
            {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
            <a href="/locations" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        </div>
        {{ Form::close() }}
    </div>
@stop

