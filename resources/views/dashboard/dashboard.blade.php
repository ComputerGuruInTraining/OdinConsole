@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Who's on Duty
@stop

@section('page-content')
    {{--<div class="col-md-12">--}}
        {{--<h1>Welcome {{$users->first_name}} {{$users->last_name}}</h1>--}}
    {{--</div>--}}
    {{--<div class="col-md-12">--}}
        {{--<div class="col-md-6">--}}
            {{--<div class="block">--}}
                    {{--<div >--}}
                        {{--Business <span class="glyphicon glyphicon-home pull-right" aria-hidden="true"></span><br>--}}
                        {{--{{$company->name }}--}}
                    {{--</div>--}}

            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-md-6">--}}
            {{--<div class="block">--}}
                {{--Owned By <span class="glyphicon glyphicon-user pull-right" aria-hidden="true"></span><br>--}}
                {{--{{$company->owner }}--}}

            {{--</div>--}}
        {{--</div>--}}

    {{--</div>--}}
    {{--<div>--}}
        {{--<h3 style="margin-left: 30px;">Your Current Users</h3>--}}
    {{--</div>--}}
    @include('dashboard.current-location-map')

@stop