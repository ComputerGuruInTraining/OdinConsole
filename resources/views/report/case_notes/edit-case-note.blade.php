@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Edit Case Note
@stop

@section('page-content')
    <div class='col-md-8 form-pages'>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            {{--/report/{caseNoteId}/{reportId}--}}
        {{ Form::open(['route' => ['updateReportCase', $data->caseNote->id, $reportId], 'method'=>'put']) }}
{{--            {{ Form::model($report, ['url' => '/report/' . $data->caseNote->id.'/'.$reportId, 'method' => 'put']) }}--}}

{{--            {{ Form::open(['url' => '/report/' . $data->caseNote->id.'/'.$reportId, 'method' => 'put']) }}--}}

            <div class='form-group'>
                {{ Form::label('title', 'Title') }}
                {{ Form::text('title', $data->caseNote->title, ['placeholder' => 'Title', 'class' => 'form-control']) }}
            </div>

            <div class='form-group'>
                {{ Form::label('desc', 'Description') }}
                {{ Form::textarea('desc', $data->caseNote->description, ['placeholder' => 'Description', 'class' => 'form-control case-desc']) }}
            </div>

            <div class='form-group form-buttons'>
                {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
                <a href ='/reports-{{$reportId}}-edit' class = "btn btn-info">Cancel</a>
            </div>

        {{ Form::close() }}
    </div>
@stop
