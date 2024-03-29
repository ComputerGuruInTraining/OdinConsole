@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Locations
@stop

@section('page-content')
    <div class="col-md-12">
        <ul class="sidebar-menu">

        <div style="padding:15px 0px 10px 0px;">
           <button type="button" class="btn btn-success" onclick="window.location.href='location-create'">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Location
            </button>
        </div>
        </ul>

        <div class='table-responsive'>
            <table class="table table-hover">
                <tr>
                    <th>Alias</th>
                    <th>Address</th>
                    <th>Notes</th>
                    <th>Manage</th>
                </tr>
                @foreach($locations as $location)
                    <tbody class='group-list'>
                    <tr>
                        <td>{{$location->name}}</td>
                        <td>{{$location->address}}</td>
                        <td>{{$location->notes}}</td>
                        <td class="column-width">
                            <a href="/location-show-{{$location->id}}"><i class="fa fa-file"></i></a>
                            <a href="/location-edit-{{$location->id}}"><i class="fa fa-edit icon-padding"></i></a>
                            <a href="/confirmdel-{{$location->id}}-{{$url}}" style="color: #990000;"><i class="fa fa-trash-o icon-padding"></i></a>
                        </td>
                    </tr>
                    </tbody>
                @endforeach
            </table>
        </div>

    </div>
@stop




