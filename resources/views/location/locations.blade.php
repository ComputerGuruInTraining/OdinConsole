@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Locations
@stop

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
                            <a href="/locations/{{$location->id}}/edit">Edit</a> | <a href="/confirm-delete/{{$location->id}}/{{$url}}" style="color: #cc0000;">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

    </div>
@stop




