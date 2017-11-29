@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    {{$location->name}}
@stop

@section('page-content')

    {{--TODO: fix display in Microsoft Edge. The page content shows as approx. 1/3 of main content area--}}
    <div class='form-pages'>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{--    {{ Form::open(['role' => 'form', 'url' => '/location-create-confirm']) }}--}}
        @include('map')

        <div class="col-md-10 margin-top">
            <div class='table-responsive'>
                <table class="table table-hover no-borders">
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
                            Location Notes:
                        </th>
                        <td>
                            {{$location->notes}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
            <div class="form-buttons padding-top">
                <a href="/location-edit-{{$location->id}}" class="btn btn-info">Edit</a>
                <a href="/confirmdel-{{$location->id}}-{{$url}}" class="btn btn-danger">Delete</a>
                {{--<a href="/location" class="btn btn-info" style="right: 20px;">Back</a>--}}
            </div>

    </div>

@stop
