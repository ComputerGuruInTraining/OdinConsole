<div class="col-md-12">
@if($notes != "Nothing Reported")
    <p class="report-table details-heading">Case Details</p>
    @if(count($data) != 0)
        @foreach($data as $index => $shiftCheck)
            @foreach ($data->get($index) as $item)
                @if(($item->title != "Nothing to Report")&&($item->case_notes_deleted_at == null))
                    <div class="padding-top content-app top-border">
                        <p>
                            <span class="col-md-3">Case ID:</span>
                            <span class="col-md-9"># {{$item->case_id}}</span>
                        </p>
                        <br/>
                        <p>
                                <span class="col-md-3">
                                Total Check In Time:
                                </span>
                            <span class="col-md-9">
                                @if(isset($item->checkDuration))
                                    {{$item->checkDuration}}
                                @else
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                @endif
                            </span>
                        </p>
                        <br/>
                        <p>
                            <span class="col-md-3">Case Note Title:</span>
                            <span class="col-md-9">{{$item->title}}</span>
                        </p>
                        <br/>
                        {{--description--}}
                        {{--todo : loop through once add the ability to add more case notes for a case id/title--}}
                        @if($item->description != null)
                            <p>
                                <span class="col-md-3">Case Notes:</span>
                                <span class="col-md-9">{{$item->description}}</span>
                            </p>
                            <br/>
                        @endif
                        {{--Images--}}
                        @if(isset($item->files))
                            @if(sizeof($item->files) > 0)
                                <span class="col-md-3">Images:</span>
                                <br/>
                                <span class="col-md-offset-3">
                                    @for($i=0; $i < sizeof($item->files); $i++)
                                        <a class="align-cols padding-right" href="{{$item->urls[$i]}}" target="_blank">
                                            <img src="{{$item->urls[$i]}}" height="125px" width="125px" class="margin-bottom"/>
                                        </a>
                                    @endfor
                                </span>
                            @endif
                        @endif
                    </div>
                @endif
            @endforeach
        @endforeach
    @endif
@endif

</div>