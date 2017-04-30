@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Confirmation Page
@stop

@section('page-content')
    {{--TODO: make a bit more robust and adaptable to different items.--}}
    <div class="form-pages">Are you sure you would like to delete <b style="color:#663974;">{{ $deleting->name}}</b>?</div>
    {{ Form::open(['url' => '/location/' . $deleting->id, 'method' => 'DELETE']) }}
    {{ Form::submit('Delete', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
@stop