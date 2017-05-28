@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Confirmation Page
@stop

@section('page-content')
    {{--TODO v1: consistent confirmation pages across the app--}}
    {{--TODO maybe: ??make a bit more robust and adaptable to different items?? see confirm-create and confirm-delete--}}
    <div class="form-pages">You have successfully {{ $theAction }}.</div>
@stop