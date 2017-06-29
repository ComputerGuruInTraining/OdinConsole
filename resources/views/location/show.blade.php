@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Location Detail
@stop

@section('page-content')
  <div class="col-md-12">
    <div class='table-responsive'>
      <table class="table table-hover">
        <tr>
          <th>
            Alias:
          </th>
          <td>
            {{$location->name}}
          </td>
        </tr>
        <tr>
          <th>
            Address:
          </th>
          <td>
            {{$location->address}}
          </td>
        </tr>
        <tr>
          <th>
            Additional Info:
          </th>
          <td>
            {{$location->notes}}
          </td>
        </tr>
      </table>

      <div class="form-group form-buttons">
        <a href="/locations/{{$location->id}}/edit">Edit</a> | <a href="/confirm-delete-location/{{$location->id}}/" style="color: #cc0000;">Delete</a>
      </div>
    </div>
  </div>
@stop
