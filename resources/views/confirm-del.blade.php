@extends('layouts.master_layout_recent_date')
@extends('sidebar')

@section('title-item')
    Confirmation Page
@stop

@section('page-content')
    <div class="confirm">Are you sure you would like to delete the item from the database?

    </div>
    <div class="delete-btns">
        {{ Form::open(['url' => '/'.$url.'-deleted-'. $id, 'method' => 'DELETE']) }}
        {{ Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        <a href="/{{$url}}" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        {{ Form::close() }}
    </div>
@stop