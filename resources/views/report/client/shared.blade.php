{{--File used by both client view and pdf--}}

{{--Check to ensure there are case notes or else an error will be thrown--}}

@if(count($data) != 0)
    @foreach($data as $index => $shiftCheck)

        <tr>
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

{{--6 scenarios:

CNR = CASE NOTE REPORTED
NTR = NOTHING TO REPORT

1.  CNR (not deleted)
    NTR (not deleted)
    ...

2.  CNR (deleted or not)

3.  NTR (deleted or not)

4.  NTR (not deleted)
    CNR (not deleted)

5.  NTR (deleted or not)
    NTR (deleted or not)

6.  CNR (not deleted)
    CNR (not deleted)

//$item->note = "Nothing to Report" due to all being deleted
7.  CNR (deleted)
    CNR (deleted)

8.  CNR (deleted)
    NTR (deleted)

9.  NTR (deleted)
    CNR (deleted)


    + 6 more for when all scenario items are deleted
    + more scenarios for when some are deleted and some are not
--}}

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
                            <td></td>
                            <td></td>
                            <td></td>
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
        {{--@endif--}}
    </tr>
@endif

