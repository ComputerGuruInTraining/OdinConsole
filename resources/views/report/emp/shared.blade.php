{{--File used by both view and pdf--}}

{{--Check to ensure there are case notes or else an error will be thrown--}}

@if(count($data) != 0)
    @foreach($data as $index => $shiftCheck)

        <tbody class="group-list">

        <tr>
            <td class="report-title" colspan="4">{{formatDatesShort($index)}}</td>
            {{--<td></td>--}}
            {{--<td></td>--}}
            {{--<td></td>--}}
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        @foreach ($data->get($index) as $item)

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

                        {{--location--}}
                        <td>{{$item->address}}</td>

                        {{--action--}}
                        <td>Case Note Reported</td>
                        <td># {{$item->cases[$a]->case_id}}</td>

                        {{--Total Time--}}
                        @if(isset($item->checkDuration))
                            <td>{{$item->checkDuration}}</td>
                        @else
                            <td><i class="fa fa-minus" aria-hidden="true"></i></td>
                        @endif

                        {{--GeoLocation uses check in geoLocation--}}
                        @if(isset($show))
                            @if($item->distance_check_in !== "0")
                                @if($item->distance_check_in <= 0.2)
                                    <td><i class="fa fa-check green-tick" aria-hidden="true"></i></td>
                                @elseif($item->distance_check_in <= 0.5)
                                    <td><i class="fa fa-check orange-tick" aria-hidden="true"></i></td>
                                @else
                                    <td><i class="fa fa-times red-cross" aria-hidden="true"></i></td>
                                @endif
                            @else
                                <td><i class="fa fa-minus" aria-hidden="true"></i></td>
                            @endif
                        @else
                            @if($item->distance_check_in !== "0")
                                @if($item->distance_check_in <= 0.2)
                                    <td>
                                        <img src="{{base_path("public/icons/if_checkmark-g_86134.png")}}"/>
                                    </td>
                                @elseif($item->distance_check_in <= 0.5)
                                    <td>
                                        <img src="{{base_path("public/icons/if_checkmark-o_86136.png")}}"/>
                                    </td>
                                @else
                                    <td>
                                        <img src="{{base_path("public/icons/if_cross_5233.png")}}"/>
                                    </td>
                                @endif
                            @else
                                <td>
                                    <img src="{{base_path("public/icons/if_minus_216340.png")}}"/>
                                </td>
                            @endif
                        @endif
                    </tr>

                    {{--then loop through the rest of the cases (starting at the index we are already at)--}}
                    @for($b = ($a + 1); $b < count($item->cases); $b++)
                        @if($item->cases[$b]->case_notes_deleted_at == null)
                            @if($item->cases[$b]->title != "Nothing to Report")

                                {{--print another row with just case ids--}}
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td># {{$item->cases[$b]->case_id}}</td>
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

                {{--location--}}
                <td>{{$item->address}}</td>

                <td>Nothing to Report</td>
                <td></td>

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

                {{--GeoLocation uses check in geoLocation--}}
                @if(isset($show))
                   @if($item->distance_check_in !== "0")
                        @if($item->distance_check_in <= 0.2)
                            <td><i class="fa fa-check green-tick" aria-hidden="true"></i></td>
                        @elseif($item->distance_check_in <= 0.5)
                            <td><i class="fa fa-check orange-tick" aria-hidden="true"></i></td>
                        @else
                            <td><i class="fa fa-times red-cross" aria-hidden="true"></i></td>
                        @endif
                    @else
                        <td><i class="fa fa-minus" aria-hidden="true"></i></td>
                    @endif
                @else
                   @if($item->distance_check_in !== "0")
                        @if($item->distance_check_in <= 0.2)
                            <td>
                                <img src="{{base_path("public/icons/if_checkmark-g_86134.png")}}"/>
                            </td>
                        @elseif($item->distance_check_in <= 0.5)
                            <td>
                                <img src="{{base_path("public/icons/if_checkmark-o_86136.png")}}"/>
                            </td>
                        @else
                            <td>
                                <img src="{{base_path("public/icons/if_cross_5233.png")}}"/>
                            </td>
                        @endif
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

                {{--location--}}
                <td>{{$item->address}}</td>

                {{--action--}}
                @if($item->title == "Nothing to Report")
                    <td class="min-width-lg">Nothing to Report</td>
                    <td></td>
                @elseif($item->deleted_at != null)
                    <td>Nothing to Report</td>
                    <td></td>
                @else
                    <td>Case Note Reported</td>
                    <td># {{$item->case_id}}</td>
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

                {{--GeoLocation uses check in geoLocation--}}
                @if(isset($show))
                   @if($item->distance_check_in !== "0")
                        @if($item->distance_check_in <= 0.2)
                            <td><i class="fa fa-check green-tick" aria-hidden="true"></i></td>
                        @elseif($item->distance_check_in <= 0.5)
                            <td><i class="fa fa-check orange-tick" aria-hidden="true"></i></td>
                        @else
                            <td><i class="fa fa-times red-cross" aria-hidden="true"></i></td>
                        @endif
                    @else
                        <td><i class="fa fa-minus" aria-hidden="true"></i></td>
                    @endif
                @else
                   @if($item->distance_check_in !== "0")
                        @if($item->distance_check_in <= 0.2)
                            <td>
                                <img src="{{base_path("public/icons/if_checkmark-g_86134.png")}}"/>
                            </td>
                        @elseif($item->distance_check_in <= 0.5)
                            <td>
                                <img src="{{base_path("public/icons/if_checkmark-o_86136.png")}}"/>
                            </td>
                        @else
                            <td>
                                <img src="{{base_path("public/icons/if_cross_5233.png")}}"/>
                            </td>
                        @endif
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
    </tr>
@endif
