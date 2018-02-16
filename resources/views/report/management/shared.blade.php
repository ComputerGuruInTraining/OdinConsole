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

            @if(count($item->cases) > 1){{--scenarios 1, 4, 5, 6--}}
            @if($item->note != "Nothing to Report"){{--scenario 1, 4, 6--}}
            {{--NB: if all cases have been deleted, will have $item->note == "Nothing to Report"--}}

            @for ($a = 0; $a < count($item->cases); $a++)
                @if($item->cases[$a]->case_notes_deleted_at == null)

                    @if($item->cases[$a]->title != "Nothing to Report"){{--scenario 1, 6, 4 (will skip the first item that is Nothing to Report)--}}

                    {{--print the whole line--}}
                    <tr>
                        <td></td>
                        <td>{{$item->timeTzCheckIn}}</td>
                        <td>{{$item->timeTzCheckOut}}</td>

                        {{--action--}}
                        <td>Case Note Reported</td>
                        <td># {{$item->cases[$a]->case_id}}</td>

                        {{--Guard Name--}}
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
                        {{--@if(isset($edit))--}}
                        {{--@if($item->case_notes_deleted_at == null)--}}
                        {{--@if($item->title != "Nothing to Report")--}}
                        {{--<td>--}}
                        {{--<a href="/delete/{{$urlCancel}}/{{$item->case_note_id}}/{{$report->id}}" style="color: #990000;">--}}
                        {{--<i class="fa fa-trash-o icon-padding"></i>--}}
                        {{--</a>--}}
                        {{--</td>--}}
                        {{--@else--}}
                        {{--<td>--}}
                        {{--<a href="/delete/{{$urlCancel}}/{{$item->case_note_id}}/{{$report->id}}" style="color: #990000;">--}}
                        {{--<i class="fa fa-trash-o"></i>--}}
                        {{--</a>--}}
                        {{--</td>--}}
                        {{--@endif--}}
                        {{--@else--}}
                        {{--<td>--}}
                        {{--<i class="fa fa-trash-o" style="color: #990000; opacity: 0.2;"></i>--}}
                        {{--</td>--}}
                        {{--@endif--}}
                        {{--@endif--}}
                    </tr>

                    {{--then loop through the rest of the cases (starting at the index we are already at)--}}

                    @for($b = ($a + 1); $b < count($item->cases); $b++)
                        @if($item->cases[$b]->case_notes_deleted_at == null)
                            @if($item->cases[$b]->title != "Nothing to Report")

                                {{--print another row with just case notes--}}
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td># {{$item->cases[$b]->case_id}}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                            @endif
                        @endif
                    @endfor

                    {{--break out of the if and the loop to ensure we don't continue to loop through the array again--}}
                    @break 2;
                    @endif
                @endif
            @endfor

            @elseif($item->note == "Nothing to Report"){{--scenario 5, 7, 8, 9 as all deleted so $item->note == Nothing to Report and no --}}
            <tr>
                <td></td>
                <td>{{$item->timeTzCheckIn}}</td>
                <td>{{$item->timeTzCheckOut}}</td>
                <td>Nothing to Report</td>
                <td></td>

                {{--Guard Name--}}
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
            </tr>
            @endif{{--@endif($item->note != "Nothing to Report")--}}
            @elseif(count($item->cases) == 1){{--else (count($item->cases) == 1 or 0--}}{{--scenarios 2, 3--}}
            <tr>
                <td></td>
                <td>{{$item->timeTzCheckIn}}</td>
                <td>{{$item->timeTzCheckOut}}</td>

                {{--action--}}
                @if($item->title == "Nothing to Report")
                    <td>Nothing to Report</td>
                    <td></td>

                @elseif($item->case_notes_deleted_at != null)
                    <td>Nothing to Report</td>
                    <td></td>
                @else
                    <td>Case Note Reported</td>
                    <td># {{$item->case_id}}</td>
                @endif

                {{--Guard Name--}}
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

            </tr>
            @endif
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
        {{--@if(isset($edit))--}}
        {{--<td></td>--}}
        {{--@endif--}}
    </tr>
@endif