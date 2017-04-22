@extends('layouts.list')
@extends('sidebar')
@extends('sidebar_custom')


@section('title')
    Locations
@stop

@section('custom-menu-title')
    Location
@stop

@section('create-link')
   "/create-location"
@stop

@section('title-item')
    Selected Location
@stop

@section('content-item')
    Details about the selected location including client, link to view on map, address 
@stop

@section('title-list')
    Locations
@stop

@section('content-list')
    Location 1<br />
    Location 2
@stop

