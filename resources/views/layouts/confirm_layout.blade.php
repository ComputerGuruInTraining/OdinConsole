@extends('layouts.master_layout')
@extends('sidebar')

{{--TODO: what to have here instead of Location? Maybe rethink the side menu design ie simplify--}}
@section('my side-menu')
    Location
@stop

{{--TODO: have a create link on this page? perhaps have as basic and have to go back to Locations for eg to create again.
or have a button to Create More Locations--}}
@section('create_link')
    <a href="/create-location"><span>Create</span></a>
@stop

@section('content-item')
    {{--TODO: make a bit more robust and adaptable to different items.--}}
    You have successfully created an item.
@stop