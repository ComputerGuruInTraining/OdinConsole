@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Confirmation Page
@stop

@section('page-content')
    <div class="form-pages">{{ $theAction }}.</div>
@stop