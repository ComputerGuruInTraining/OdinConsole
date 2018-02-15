{{--File used by both client view and pdf--}}

{{--Check to ensure there are case notes or else an error will be thrown--}}

@if(count($data) != 0)
    @foreach($data as $index => $shiftCheck)

        <tr>
            <td class="report-title" colspan="4">{{formatDatesShort($index)}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            {{--@if(isset($edit))--}}
                {{--<td></td>--}}
            {{--@endif--}}
        </tr>

        @foreach ($data->get($index) as $item)
            <tbody class="alt-cols">
            @if($item->uniqueShiftCheckId != null)



                {{--
                1. if $item->cases > 1
                if $item->note != "Nothing to Report"
                at least one case is something to report


                for ($a = 0; $a < count($item->cases); $a++)
                    if($item->cases[$a]->title != "Nothing to Report")
                    print the whole line

                    then loop through the rest of the cases (starting at the index we are already at)
                    for($b = $a; $b < (count(($item->cases)) - ($a+1)); $b++)
                            if($item->cases[$b]->title != "Nothing to Report")
                                print another row with just case notes
                            endif

                    endfor


                endfor



                2. else if $item->note == "Nothing to Report"
                then all cases are nothing to report
                so just print once

                3. $item->cases == 1
                just print once




                --}}

                <tr>
                <td></td>
                <td>{{$item->timeTzCheckIn}}</td>
                <td>{{$item->timeTzCheckOut}}</td>

                {{--action--}}
                @if($item->title == "Nothing to Report")
                    <td>Nothing to Report</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                @elseif($item->case_notes_deleted_at != null)
                    <td>Nothing to Report</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                @else
                    <td>Case Note Reported</td>
                    <td># {{$item->case_id}}</td>
                    <td>{{$item->title}}</td>

                    {{--description--}}
                    @if(isset($item->shortDesc))
                        <td>{{$item->shortDesc}}</td>
                    @else
                        <td>{{$item->description}}</td>
                    @endif

                    {{--Image--}}

                    @if($item->hasImg == "Y")
                        <td>Yes</td>
                    @else
                        <td></td>
                    @endif
                @endif

                {{--Edit btns for edit view only--}}
                {{--@if(isset($edit))--}}
                    {{--@if($item->case_notes_deleted_at == null)--}}
                        {{--@if($item->title != "Nothing to Report")--}}
                            {{--<td>--}}
                                {{--<a href="/delete/{{$urlCancel}}/{{$item->case_note_id}}/{{$report->id}}" style="color: #990000;">--}}
{{--            @if($item->uniqueShiftCheckId != null){{--if the uniqueShiftCheckId holds a value, the cases array will be declared --}}
{{--6 scenarios:

CNR = CASE NOTE REPORTED
NTR = NOTHING TO REPORT

1.  CNR
    NTR
    ...

2.  CNR

3.  NTR

4.  NTR
    CNR

5.  NTR
    NTR

6.  CNR
    CNR
--}}

            @if(count($item->cases) > 1){{--scenarios 1, 4, 5, 6--}}
                    @if($item->note != "Nothing to Report"){{--scenario 1, 4, 6--}}
                    {{--at least one case is something to report--}}

                        @for ($a = 0; $a < count($item->cases); $a++)
                            @if($item->cases[$a]->case_notes_deleted_at == null)
                                {{--NB: if all cases have been deleted, will have $item->note == "Nothing to Report"--}}
                                @if($item->cases[$a]->title != "Nothing to Report"){{--scenario 1, 6, 4 (will skip the first item that is Nothing to Report)--}}

                                    {{--print the whole line--}}

                                <tr>
                                    <td></td>
                                    <td>{{$item->timeTzCheckIn}}</td>
                                    <td>{{$item->timeTzCheckOut}}</td>

                                    {{--action--}}
                                    {{--@if($item->title == "Nothing to Report")--}}
                                        {{--<td>Nothing to Report</td>--}}
                                        {{--<td></td>--}}
                                        {{--<td></td>--}}
                                        {{--<td></td>--}}
                                        {{--<td></td>--}}
                                    {{--@elseif($item->case_notes_deleted_at != null)--}}
                                        {{--<td>Nothing to Report</td>--}}
                                        {{--<td></td>--}}
                                        {{--<td></td>--}}
                                        {{--<td></td>--}}
                                        {{--<td></td>--}}
                                    {{--@if--}}
                                        <td>Case Note Reported</td>
                                        <td># {{$item->cases[$a]->case_id}}</td>
                                        <td>{{$item->cases[$a]->title}}</td>

                                        {{--description--}}
                                        @if(isset($item->cases[$a]->shortDesc))
                                            <td>{{$item->cases[$a]->shortDesc}}</td>
                                        @else
                                            <td>{{$item->cases[$a]->description}}</td>
                                        @endif

                                        {{--Image--}}

                                        @if($item->cases[$a]->hasImg == "Y")
                                            <td>Yes</td>
                                        @else
                                            <td></td>
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
            @else
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
                                                {{--<td>more cases to report</td>--}}
                                                <td></td>
                                                <td></td>
                                                <td></td>

                                                {{--action--}}
                                                {{--@if(($item->title != "Nothing to Report")||($item->case_notes_deleted_at != null))--}}
                                                    {{--<td>Nothing to Report</td>--}}
                                                    {{--<td></td>--}}
                                                    {{--<td></td>--}}
                                                    {{--<td></td>--}}
                                                    {{--<td></td>--}}
                                                {{--@else--}}
                                                {{--@if()--}}
                                                    <td></td>
                                                    <td># {{$item->cases[$b]->case_id}}</td>
                                                    <td>{{$item->cases[$b]->title}}</td>

                                                    {{--description--}}
                                                    @if(isset($item->cases[$b]->shortDesc))
                                                        <td>{{$item->cases[$b]->shortDesc}}</td>
                                                    @else
                                                        <td>{{$item->cases[$b]->description}}</td>
                                                    @endif

                                                    {{--Image--}}
                                                    @if($item->cases[$b]->hasImg == "Y")
                                                        <td>Yes</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                {{--@endif--}}
                                            </tr>

                                        @endif
                                    @endif
                                @endfor

                                {{--break to ensure we don't continue to loop through the array again--}}
                                    @break 2;
                                @endif
                            @endif
                        @endfor
                    {{--@elseif($item->note == "Nothing to Report")--}}{{--scenario 5--}}

                    @endif{{--@endif($item->note != "Nothing to Report")--}}
            @elseif(count($item->cases) == 1){{--else (count($item->cases) == 1 or 0--}}{{--scenarios 2, 3--}}
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{$item->timeTzCheckIn}}</td>
                    <td>{{$item->timeTzCheckOut}}</td>

                    {{--action--}}
                    @if(($item->title != "Nothing to Report")||($item->case_notes_deleted_at != null))
                    @if($item->title == "Nothing to Report")
                        <td>Nothing to Report</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @elseif($item->case_notes_deleted_at != null)
                        <td>Nothing to Report</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @else
                        <td>Case Note Reported</td>
                        <td># {{$item->case_id}}</td>
                        <td>{{$item->title}}</td>

                        {{--description--}}
                        @if(isset($item->shortDesc))
                            <td>{{$item->shortDesc}}</td>
                        @else
                            <td>{{$item->description}}</td>
                        @endif

                        {{--Image--}}
                        @if($item->hasImg == "Y")
                            <td>Yes</td>
                        @else
                            <td></td>
                        @endif
                    @endif
                </tr>
            @endif
            {{--@endif--}}
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
        {{--<td></td>--}}
        {{--@endif--}}
    </tr>
@endif@endif@endif@endif@endif

