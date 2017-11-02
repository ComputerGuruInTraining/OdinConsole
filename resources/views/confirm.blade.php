{{--Usage: generic confirmation page using a layouts.master with app sidebar and header. Useful when logged into app--}}

@extends('layouts.master_layout_recent_date')
@extends('sidebar')

@section('title-item')
    Confirmation Page
@stop

@section('page-content')
    <div class="confirm col-md-8 line-space">{{ $theAction }}.</div>
@stop