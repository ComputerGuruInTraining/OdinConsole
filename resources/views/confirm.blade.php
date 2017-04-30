@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Confirmation Page
@stop

@section('page-content')
    {{--TODO: make a bit more robust and adaptable to different items.--}}
    <div class="form-pages">You have successfully {{ $theAction }} <b style="color:#663974;">{{ $theData }}</b>.</div>
@stop