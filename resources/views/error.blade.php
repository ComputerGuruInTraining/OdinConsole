{{--Usage: generic eror page using layouts.master_layout and sidebar and header. Useful when Logged into app.--}}

@extends('layouts.master_layout_recent_date')
@extends('sidebar')

@section('title-item')
    Error Page
@stop

@section('page-content')
    <div class="confirm col-md-8 line-space">{{ $error }}</div>
@stop