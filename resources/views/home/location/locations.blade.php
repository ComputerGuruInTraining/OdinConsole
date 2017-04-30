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

{{--TODO: as using an absolute url, change the url once deploying and domain set.--}}
@section('content-item')
    {{$displayItem->name}}
    <a href="http://localhost:8000/location/{{ $displayItem->id }}/edit" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
    <a href="/confirm-delete/{{ $displayItem->id }}/" class="btn btn-info pull-left" style="margin-right: 3px;">Delete</a>


    {{--<a href="/{{ $displayItem->id }}/delete" class="btn btn-info pull-left" style="margin-right: 3px;" >Delete {{ method_field('DELETE') }}</a>--}}
    {{--{{ Form::model($locations, ['route' => ['location.destroy', $locations[$displayItem->id]], 'method' => 'delete']) }}--}}
    {{--<button type="submit" class="btn btn-sm btn-default"><a href="http://localhost:8000/location/{{ $displayItem->id }}" class="btn btn-info pull-left" style="margin-right: 3px;">Delete</a></button>--}}
    {{--{{ Form::close() }}--}}
@stop

@section('content-list')
    @foreach($locations as $dbLocation)

    <li><a href="/{{ $dbLocation->id }}/">{{ $dbLocation->name }}</a></li>
<br>
    @endforeach
@stop

@section('title-list')
    Locations
@stop

{{--TODO: improve display--}}
{{--TODO: sort ascending order or by client--}}


