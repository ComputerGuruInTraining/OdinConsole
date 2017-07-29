@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Edit Case Note
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
        {{ Form::open(['route' => ['case-notes.update', $data->caseNote->id], 'method'=>'put']) }}
            {{--<div class='form-group'>--}}
                {{--{{ Form::label('date', 'Date') }}--}}
                {{--{{ Form::text('date', $noteDate, ['placeholder' => 'Date', 'class' => 'form-control']) }}--}}
            {{--</div>--}}

            {{--<div class='form-group'>--}}
                {{--{{ Form::label('time', 'Time') }}--}}
                {{--{{ Form::text('time', $time, ['placeholder' => 'Time', 'class' => 'form-control']) }}--}}
            {{--</div>--}}

            <div class='form-group'>
                {{ Form::label('title', 'Title') }}
                {{ Form::text('title', $data->caseNote->title, ['placeholder' => 'Title', 'class' => 'form-control']) }}
            </div>

            <div class='form-group'>
                {{ Form::label('desc', 'Description') }}
                {{ Form::text('desc', $data->caseNote->description, ['placeholder' => 'Description', 'class' => 'form-control']) }}
            </div>

            <div class='form-group form-buttons'>
                {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
                <a href ='/reports' class = "btn btn-info">Cancel</a>
            </div>

        {{ Form::close() }}
    </div>
@stop
