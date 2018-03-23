{{--Usage: generic confirmation page using layouts.master_layout without app sidebar and header. Useful when not logged into app--}}
@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
Upgrade Subscription
@stop

@section('custom-scripts')
    @include('company-settings.upgrade_js')
@stop

@section('page-content')
    @include('company-settings.upgrade_layout')
@stop