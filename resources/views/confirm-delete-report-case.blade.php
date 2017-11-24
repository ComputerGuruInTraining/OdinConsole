@extends('layouts.master_layout_recent_date')
@extends('sidebar')

@section('title-item')
    Confirmation Page
@stop

@section('page-content')
    <div class="confirm">Are you sure you would like the item to be deleted from the database?

    </div>
    <div class="delete-btns">
        {{---{reportId}-delete-item-{id}--}}
        {{--report/{reportId}/delete/{id}--}}

        {{--pass through reportId and CaseNoteId--}}
        {{ Form::open(['url' => '/report/'.$reportId.'/delete/' . $id, 'method' => 'DELETE']) }}

        {{--        {{ Form::open(['url' => '/'.$urlDel.'/' . $id, 'method' => 'DELETE']) }}--}}
        {{ Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        <a href="/{{$urlCancel}}" class="btn btn-info" style="margin-right: 3px;">Cancel</a>
        {{ Form::close() }}
    </div>
@stop