@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Case Notes
@stop

@section('page-content')
    <div class='col-md-12'>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class='table-responsive padding-top'>
            <table class="table table-hover">
                <tr>
                    {{--<th>Location</th>--}}
                    <th>Case Id</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Reporting Guard</th>
                    <th>Actions</th>
                </tr>
                {{--Check to ensure there are case notes or else an error will be thrown--}}
{{--                @if(count($cases) != 0)--}}

                @foreach($cases as $index => $note)
                    {{--@foreach($locations as $location)--}}
                        <tbody class="group-list">

                        {{--@if($location->id == $note->location_id)--}}
                            {{--<tr>--}}
                                {{--<td>{{$location->name}}</td>--}}
                            {{--</tr>--}}
                        {{--@endif--}}
                        <tr>
                            <td class="report-title" colspan="4">{{$index}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            {{--<td></td>--}}
                        </tr>
                        @foreach ($cases->get($index) as $item)
                            <tr>
                                {{--<td></td>--}}
                                <td class="padding-left max-width">{{$item->case_id}}</td>
                                <td>{{$item->date}}</td>
                                <td class="min-width">{{$item->time}}</td>
                                <td>{{$item->title}}</td>
                                <td class="desc-max-width">{{$item->description}}</td>
                                <td>{{$item->img}}</td>
                                <td class="max-width word-wrap">
                                    <a href="https://odinliteapi.scm.azurewebsites.net/api/vfs/site/storage/app/images/1506220921465.jpeg" target="_blank">Image</a>
                                </td>
                                <td>{{$item->first_name}} {{$item->last_name}}</td>
                                <td><a href="/case-notes/{{$item->id}}/edit" class="edit-links">Edit</a>
                                    |
                                    <a href="/confirm-delete/{{$item->id}}/{{$url}}"
                                       style="color: #990000;">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    {{--@endforeach--}}
                @endforeach

                {{--@else--}}
                {{--<tr>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}
                {{--</tr>--}}

                {{--@endif--}}

            </table>
        </div>
    </div>
@stop
