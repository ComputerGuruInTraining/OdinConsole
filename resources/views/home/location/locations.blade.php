@extends('layouts.list')
{{--@extends('sidebar')--}}
@extends('sidebar_custom')


@section('title')
    Locations
@stop

@section('custom-menu-title')
    Location
@stop

@section('create-link')
    "http://localhost:8000/location/create"
@stop

@section('edit-link')
    "http://localhost:8000/location/{{ $displayItem->id }}/edit"
@stop

@section('delete-link')
    "/confirm-delete/{{ $displayItem->id }}/"
@stop

@section('title-item')
    Selected Location
@stop
{{--TODO @deployment: as using an absolute url, change the url once deploying and domain set.--}}
{{--v2 TODO v2: next and previous buttons in the selected location section--}}
@section('content-item')
    {{--TODO v1 low-priority: Show address on map in selected location area--}}
    <table>
        <tr class="details-tr">
            <td class="item-details">Address Alias:</td>
            <td>{{$displayItem->name}}</td>
        </tr>
        <tr class="details-tr">
            <td class="item-details">Street Address:</td>
            <td>{{$displayItem->address}}</td>
        </tr>
        <tr class="details-tr">
        <td class="item-details">Additional Info:</td>
            <td>{{$displayItem->additional_info or 'None Provided'}}</td>
        </tr>
    </table>
    <div class="manage-btns">
        <a href="http://localhost:8000/location/{{ $displayItem->id }}/edit" class="btn btn-info" style="margin-right: 3px;">Edit</a>
        <a href="/confirm-delete/{{ $displayItem->id }}/" class="btn btn-danger" style="margin-right: 3px;">Delete</a>
    </div>
@stop

{{--TODO v2 or v1 low-priority: improve list so that doesn't run endlessly down the page--}}
@section('content-list')
    @foreach($locations->sortBy('name') as $dbLocation)
        <div class="list"><a href="/{{ $dbLocation->id }}/">{{ $dbLocation->name }}</a></div>
        <br>
    @endforeach
@stop

@section('title-list')
    Locations
@stop



