@extends('layouts.master_layout_recent_date')
@extends('sidebar')

@section('title-item')
    Confirmation Page
@stop

@section('page-content')
    <div class="col-md-10">
        <div class="padding">
            {{$theMsg}}.
        </div>
        <div class="padding">
            <a href="/{{$url}}" class="btn btn-info" style="margin-right: 3px;">{{$btnText}}</a>
            <a href="{{ route('pdf',['id' => $reportId, 'download'=>'pdf']) }}" class="btn btn-primary">Download PDF</a>


        </div>
    </div>
@stop