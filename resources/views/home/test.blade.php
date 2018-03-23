@extends('layouts.master_layout_alt_header')

@section('title-item') Test page @stop

@section('page-content')
    <div>
        <?php echo $signature?>
        {{--%252FyRVzgyQeGB8x0N6ZruXbWFla3KrP3l3%252BV3TcHG%252BRU8%253D--}}
<br/>

            <?php echo $myUrl . rawurldecode($signature)?>
            <br/>


            <img src="<?php echo $myUrl . rawurldecode($signature)?>" alt="{{$myUrl.$signature}}" height="250px" width="250px" class="margin-bottom margin-right"/>

            <br/>
            <img src="<?php echo $myUrl . rawurldecode($signature)?>" alt="<?php echo $myUrl . rawurldecode($signature)?>" height="250px" width="250px" class="margin-bottom margin-right"/>

            {{--%2FyRVzgyQeGB8x0N6ZruXbWFla3KrP3l3%2BV3TcHG%2BRU8%3D--}}
    </div>
    {{--alt="{{$myUrl.$signature}}" --}}
         {{--height="250px" width="250px" class="margin-bottom margin-right"/>--}}
@stop