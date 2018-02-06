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
                    <th>Case Id</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Reporting Guard</th>
                    <th>Manage</th>
                </tr>

                @foreach($cases as $index => $note)
                    <tbody class="group-list">
                    <tr>
                        <td class="report-title" colspan="4">{{$index}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @foreach ($cases->get($index) as $item)
                        <tr>
                            <td class="padding-left max-width">{{$item->case_id}}</td>

                            @if(isset($item->date))
                                <td>{{$item->date}}</td>
                            @else
                                <td>GeoData n/a</td>
                            @endif

                            @if(isset($item->time))
                                <td class="min-width">{{$item->time}}</td>
                            @else
                                <td>GeoData n/a</td>
                            @endif

                            <td>{{$item->title}}</td>
                            <td class="desc-max-width">{{$item->description}}</td>

                            @if($item->hasImg == "Y")
                                @if(isset($item->files))

                                    @if(sizeof($item->files) > 0)
                                        {{--first photo in array--}}
                                        {{--<td><a href="{{$item->urls[0]}}" target="_blank">Download {{$item->files[0]}}</a></td>--}}
                                        <td><img src="{{$item->urls[0]}}" height="25px" width="25px"/></td>
                                    @else
                                        {{--v2 uploads--}}
                                        {{--todo: remove once not using v2 mobile anymore--}}
                                        <td><a href="{{$item->url}}" target="_blank">WIP</a></td>
                                        {{--<td><img src="{{$item->url}}"/></td>--}}

                                    @endif
                                @else
                                    {{--v2 uploads--}}
                                    {{--todo: remove once not using v2 mobile anymore--}}
                                    <td><a href="{{$item->url}}" target="_blank">WIP</a></td>

                                @endif
                            @else
                                <td>{{$item->hasImg}}</td>
                            @endif

                            <td>{{$item->first_name}} {{$item->last_name}}</td>

                            @if($item->title != "Nothing to Report")
                                <td><a href="/case-notes/{{$item->id}}/edit" class="edit-links"><i class="fa fa-edit"></i></a>
                                    <a href="/confirm-delete/{{$item->id}}/{{$url}}"
                                       style="color: #990000;"><i class="fa fa-trash-o icon-padding"></i></a>
                                </td>
                            @else
                                <td>
                                    <a href="/confirm-delete/{{$item->id}}/{{$url}}"
                                       style="color: #990000;"><i class="fa fa-trash-o"></i></a>
                                </td>
                            @endif
                        </tr>

                        {{--another row for case notes which have more than 1 photo--}}
                        @if(isset($item->files))
                            @if(sizeof($item->files) > 1)
                                @for($i=1; $i < sizeof($item->files); $i++)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><img src="{{$item->urls[$i]}}" height="25px" width="25px"/></td>
                                        {{--<td><a href="{{$item->urls[$i]}}" target="_blank">Download Image {{$i + 1}}</a></td>--}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endfor
                            @endif
                        @endif

                    @endforeach
                    </tbody>
                @endforeach

            </table>
        </div>
    </div>
@stop
