@extends('layouts.master_layout')
@extends('sidebar')


@section('my side-menu')
    Location
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

