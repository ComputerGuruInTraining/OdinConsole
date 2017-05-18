@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Confirmation Page
@stop

@section('page-content')
    {{--TODO: make a bit more robust and adaptable to different items.--}}
    <div class="padding">
        You have successfully added a {{$entity}} for <b style="color:#663974;">{{ $theData }}</b>.
    </div>
    <div class="padding">
        <a href="/rosters/create" class="btn btn-info" style="margin-right: 3px;">Add {{$entity}}</a>
    </div>
    {{--<div class="form-pages">The shift has been saved as <b style="color:#663974;">{{ $theName }}</b>.</div>--}}

@stop