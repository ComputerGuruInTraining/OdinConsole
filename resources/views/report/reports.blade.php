@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Reports
@stop

@section('page-content')
    <div>
        {{$reports->location_id}}
    </div>

@stop