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
   "location/create"
@stop

@section('edit-link')
    {{--"location/{{$locations->id}}/edit-location"--}}
@stop

@section('title-item')
    Selected Location
@stop

@section('content-item')
    {{$displayItem}}

@stop

@section('content-list')
    @foreach($locations as $dbLocation)

    <li>{{ $dbLocation->name }}<a href="/{{ $dbLocation->id }}/location/" class="btn btn-info pull-left" style="margin-right: 3px;">Details</a><a href="location/{{ $dbLocation->id }}/edit" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a></li>
<br>
    @endforeach
@stop

@section('title-list')
    Locations
@stop

{{--TODO: improve display--}}
{{--TODO: sort ascending order or by client--}}


