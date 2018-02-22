<div class="col-md-12 case-details">
@if($notes != "Nothing Reported")
    <p class="table-report details-heading">Case Details</p>
    @if(count($data) != 0)
        @foreach($data as $index => $shiftCheck)
            @foreach ($data->get($index) as $item)
                @if(($item->title != "Nothing to Report")&&($item->case_notes_deleted_at == null))
                    <div class="padding-top content-app top-border">
                        <p>
                            <span>Case ID:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            <span class="col-md-9"># {{$item->case_id}}</span>
                        </p>
                        <p>
                            <span>Total Check In Time:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            <span class="col-md-9">
                                @if(isset($item->checkDuration))
                                    {{$item->checkDuration}}
                                @else
                                    -
                                @endif
                            </span>
                        </p>
                        <p>
                            <span>Case Note Title:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            <span class="col-md-9">{{$item->title}}</span>
                        </p>
                        {{--description--}}
                        {{--todo : loop through once add the ability to add more case notes for a case id/title--}}
                        @if($item->description != null)
                            <p>
                                <span>Case Notes:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <span class="col-md-9">{{$item->description}}</span>
                            </p>
                        @endif
                        {{--Images--}}
                        @if(isset($item->files))
                            @if(sizeof($item->files) > 0)
                                <span>Images:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <br/>
                                <span class="margin-left-huge">
                                    @for($i=0; $i < sizeof($item->files); $i++)
                                        <img src="{{$item->urls[$i]}}" height="150px" width="150px" class="margin-bottom margin-right"/>
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