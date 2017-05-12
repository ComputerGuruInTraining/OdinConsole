@extends('layouts.list')
@extends('sidebar_custom')

@section('title')
    Rosters
@stop

@section('custom-menu-title')
    Roster
@stop

@section('create-link')
    "/rosters/create"
@stop

@section('title-item')
    Most Recent Roster or ??
@stop
@section('content-item')
    <p>Display most recently created roster here???? OR Create button?? or ??</p>
    <table>
        <tr class="details-tr">
            <td class="item-details">Roster Name:</td>
            <td></td>
        </tr>
        <tr class="details-tr">
            <td class="item-details">Roster Date:</td>
            <td></td>
        </tr>
        <tr class="details-tr">
            <td class="item-details">Roster Details eg Employee Name and Location:</td>
            <td></td>
        </tr>
    </table>
    <div>
        <p>Btn or just link in sidebar??</p>
        <a href="/rosters/create" class="btn btn-info" style="margin-right: 3px;">Create</a>
        {{--<a href="#" class="btn btn-info" style="margin-right: 3px;">Edit</a>--}}
        {{--<a href="#" class="btn btn-danger" style="margin-right: 3px;">Delete</a>--}}
    </div>
@stop

@section('title-list')
    Rosters
@stop

@section('content-list')
    <p>Display Saved Rosters here</p>
    {{--@foreach($locations->sortBy('name') as $dbLocation)--}}
        {{--<div class="list"><a href="/{{ $dbLocation->id }}/">{{ $dbLocation->name }}</a></div>--}}
        {{--<br>--}}
    {{--@endforeach--}}
@stop



