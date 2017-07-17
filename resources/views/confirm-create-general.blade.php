@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Confirmation Page
@stop

@section('page-content')
    {{--TODO: make a bit more robust and adaptable to different items.--}}
    <div class="padding">
        {{$theMsg}}.
    </div>
    <div class="padding">
        <a href="/{{$url}}/create" class="btn btn-success" style="margin-right: 3px;">{{$btnText}}</a>
    </div>

@stop