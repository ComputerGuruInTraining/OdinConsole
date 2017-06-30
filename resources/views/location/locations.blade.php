@extends('layouts.master_layout')
@extends('sidebar')

{{--@section('title')--}}
    {{--Locations--}}
{{--@stop--}}

{{--@section('custom-menu-title')--}}
    {{--Location--}}
{{--@stop--}}

{{--@section('create-link')--}}
    {{--"http://localhost:8000/location/create"--}}
{{--@stop--}}

{{--@section('edit-link')--}}
    {{--"http://localhost:8000/location/{{ $displayItem->id }}/edit"--}}
{{--@stop--}}

{{--@section('delete-link')--}}
    {{--"/confirm-delete-location/{{ $displayItem->id }}/"--}}
{{--@stop--}}

@section('title-item')
    Locations
@stop
{{--TODO @deployment: as using an absolute url, change the url once deploying and domain set.--}}
{{--v2 TODO v2: next and previous buttons in the selected location section--}}
{{--@section('content-item')--}}
    {{--TODO v1 low-priority: Show address on map in selected location area--}}
    {{--<table>--}}
        {{--<tr class="details-tr">--}}
            {{--<td class="item-details">Address Alias:</td>--}}
            {{--<td>{{$displayItem->name}}</td>--}}
        {{--</tr>--}}
        {{--<tr class="details-tr">--}}
            {{--<td class="item-details">Street Address:</td>--}}
            {{--<td>{{$displayItem->address}}</td>--}}
        {{--</tr>--}}
        {{--<tr class="details-tr">--}}
            {{--<td class="item-details">Additional Info:</td>--}}
            {{--<td>{{$displayItem->notes or 'None Provided'}}</td>--}}
        {{--</tr>--}}
    {{--</table>--}}
    {{--<div class="manage-btns">--}}
        {{--<a href="/locations/{{$displayItem->id}}/edit" class="btn btn-info" style="margin-right: 3px;">Edit</a>--}}
        {{--<a href="/confirm-delete-location/{{ $displayItem->id }}/" class="btn btn-danger" style="margin-right: 3px;">Delete</a>--}}
    {{--</div>--}}
{{--@stop--}}


{{--@section('title-list')--}}
    {{--Locations--}}
{{--@stop--}}

@section('page-content')
    <div class="col-md-12">

        <div style="padding:15px 0px 10px 0px;">
            <button type="button" class="btn btn-success" onclick="window.location.href='locations/create'">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Location
            </button>
        </div>

        <div class='table-responsive'>
            <table class="table table-hover">
                <tr>
                    <th>Alias</th>
                    <th>Address</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
                @foreach($locations as $location)
                    <tr>
                        <td>{{$location->name}}</td>
                        <td>{{$location->address}}</td>
                        <td>{{$location->notes}}</td>
                        <td class="column-width">
                            <a href="/locations/{{$location->id}}">View</a> | <a href="/locations/{{$location->id}}/edit">Edit</a> | <a href="/confirm-delete-location/{{$location->id}}/" style="color: #cc0000;">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

    </div>
@stop




