@extends('layouts.master_layout')
@extends('sidebar')


@section('title-item')
    Add Location
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

        {{ Form::open(['role' => 'form', 'url' => '/location-create-confirm']) }}
        @include('map')
        <div class='form-group padding-top'>
            {{ Form::label('name', 'Address Alias *') }}
            {{--if routing to page via back btn on Confirm Location page, display the input that was originally entered--}}
        @if(isset($aliasConfirm))
                 {{ Form::text('name', $aliasConfirm, ['class' => 'form-control', 'onkeypress'=>'return noenter()']) }}
            @else
                {{ Form::text('name', null, ['placeholder' => 'eg UC Building 25', 'class' => 'form-control', 'onkeypress'=>'return noenter()']) }}

            @endif
        </div>

        <div class='form-group'>
            {{ Form::label('info', 'Location Notes') }}
            {{--if routing to page via back btn on Confirm Location page, display the input that was originally entered--}}
            @if(isset($notesConfirm))
                 {{ Form::text('info', $notesConfirm, ['class' => 'form-control', 'onkeypress'=>'return noenter()']) }}
            @else
                {{ Form::text('info', null, ['placeholder' => 'ie instructions that always apply to the location, or building name, company name, etc.',
                             'class' => 'form-control', 'onkeypress'=>'return noenter()']) }}
            @endif
        </div>

        <div class='form-group form-buttons'>
            {{ Form::submit('Confirm', ['class' => 'btn btn-primary', 'onkeypress'=>'return noenter()']) }}
            <a href="/location" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        </div>
        {{ Form::close() }}
    </div>
@stop

