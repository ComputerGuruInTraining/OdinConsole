{{--File used by both view and pdf--}}

{{--Check to ensure there are case notes or else an error will be thrown--}}

@if(count($data) != 0)
    @foreach($data as $index => $shiftCheck)

        {{--<tbody class="group-list">--}}

        <tr class="report-title-bg">
            <td class="report-title" colspan="4">{{formatDatesShort($index)}}</td>
            {{--<td></td>--}}
            {{--<td></td>--}}
            {{--<td></td>--}}
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            @if(isset($edit))
                <td></td>
            @endif
        </tr>

        @foreach ($data->get($index) as $item)
            <tbody class="alt-cols">
            <tr>
                <td></td>
                <td>{{$item->timeTzCheckIn}}</td>
                <td>{{$item->timeTzCheckOut}}</td>

                {{--action--}}
                @if($item->title == "Nothing to Report")
                    <td>Nothing to Report</td>
                    <td></td>
                @elseif($item->case_notes_deleted_at != null)
                    <td class="tp-data">Nothing to Report</td>
                    <td></td>
                @else
                    <td>Case Note Reported</td>
                    <td># {{$item->case_id}}</td>
                @endif

                {{--Guard ID--}}
                @if(isset($item->user))
                    <td>{{$item->user}}</td>
                @else
                    <td></td>
                @endif
                {{--Total Time--}}
                    @if(isset($item->checkDuration))
                        {{--@if($item->checkDuration < 1)--}}
                        {{--<td> < 1</td>--}}
                        {{--@else--}}
                        <td>{{$item->checkDuration}}</td>
                        {{--@endif--}}
                    @else
                        <td><i class="fa fa-minus" aria-hidden="true"></i></td>
                    @endif

                {{--GeoLocation--}}
                @if(isset($show))
                    @if($item->withinRange == 'yes')
                        <td><i class="fa fa-check green-tick" aria-hidden="true"></i></td>
                    @elseif($item->withinRange == 'ok')
                        <td><i class="fa fa-check orange-tick" aria-hidden="true"></i></td>
                    @elseif($item->withinRange == 'no')
                        <td><i class="fa fa-times red-cross" aria-hidden="true"></i></td>
                    @else
                        <td><i class="fa fa-minus" aria-hidden="true"></i></td>
                    @endif
                @else
                    @if($item->withinRange == 'yes')
                        <td>
                            <img src="{{base_path("public/icons/if_checkmark-g_86134.png")}}"/>
                        </td>
                    @elseif($item->withinRange == 'ok')
                        <td>
                            <img src="{{base_path("public/icons/if_checkmark-o_86136.png")}}"/>
                        </td>
                    @elseif($item->withinRange == 'no')
                        <td>
                            <img src="{{base_path("public/icons/if_cross_5233.png")}}"/>
                        </td>
                    @else
                        <td>
                            <img src="{{base_path("public/icons/if_minus_216340.png")}}"/>
                        </td>
                    @endif
                @endif

                {{--Edit btns for edit view only--}}
                {{--FIXME: wrong case_note_id being sent from view to url--}}
                {{--@if(isset($edit))--}}
                    {{--@if($item->case_notes_deleted_at == null)--}}

                        {{--@if($item->title != "Nothing to Report")--}}

                            {{--<td><a href="/edit-case-notes/{{$item->case_note_id}}/reports/{{$report->id}}" class="edit-links">--}}
                                    {{--<i class="fa fa-edit"></i></a>--}}
                                {{--<a href="/delete/{{$urlCancel}}/{{$item->case_note_id}}/{{$report->id}}" style="color: #990000;">--}}
                                    {{--<i class="fa fa-trash-o icon-padding"></i></a>--}}
                            {{--</td>--}}
                        {{--@else--}}
                            {{--<td>--}}
                                {{--<a href="/delete/{{$urlCancel}}/{{$item->case_note_id}}/{{$report->id}}" style="color: #990000;">--}}
                                    {{--<i class="fa fa-trash-o"></i></a>--}}
                            {{--</td>--}}
                        {{--@endif--}}
                    {{--@else--}}
                        {{--<td>--}}
                            {{--<i class="fa fa-trash-o" style="color: #990000; opacity: 0.2;"></i>--}}
                        {{--</td>--}}
                    {{--@endif--}}
                {{--@endif--}}
            </tr>
            </tbody>
        @endforeach
    @endforeach
@else
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        @if(isset($edit))
            <td></td>
        @endif
    </tr>
@endif