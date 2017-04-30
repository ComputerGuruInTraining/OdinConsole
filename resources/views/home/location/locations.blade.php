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

    {{ $displayItem->name }}
@stop

@section('title-list')
    Locations
@stop

{{--TODO: improve display--}}
{{--TODO: sort ascending order--}}
@section('content-list')
    @foreach($locations as $dbLocation)
        {{--<button onclick={{$controller->selectedLocation($dbLocation)}}>--}}
            {{--{{$controller->selectedLocation($this->dbLocation)}}'>--}}
          <li>{{ $dbLocation->name }}</li><a href="location/{{ $dbLocation->id }}/edit" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
      {{--</button>--}}
    @endforeach
@stop

