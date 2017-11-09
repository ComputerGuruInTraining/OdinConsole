@extends('layouts.master_layout_recent_date')
@extends('sidebar')

@section('title-item')
    Confirmation Page
@stop

@section('page-content')
    {{--TODO: make a bit more robust and adaptable to different items.--}}
    <div class="padding">
        You have successfully added a {{$entity}} for <b style="color:#4d2970;">{{ $theData }}</b>.
    </div>
    <div class="padding">
        <a href="/{{$url}}/create" class="btn btn-success" style="margin-right: 3px;">Add {{$entity}}</a>
    </div>
    {{--<div class="form-pages">The shift has been saved as <b style="color:#4d2970;">{{ $theName }}</b>.</div>--}}

@stop