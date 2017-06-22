@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Confirmation Page
@stop

@section('page-content')
    {{--TODO: make a bit more robust and adaptable to different items.--}}
    <div class="confirm">Are you sure you would like to delete {{ $fieldDesc}}?</div>
    <div class="delete-btns">
        {{ Form::open(['url' => '/'.$url.'/' . $id, 'method' => 'DELETE']) }}
        {{ Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        <a href="/location" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        {{ Form::close() }}
    </div>
@stop