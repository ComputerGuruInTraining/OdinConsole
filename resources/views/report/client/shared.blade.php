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
            @else
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>

                    {{--action--}}
                    @if(($item->title != "Nothing to Report")||($item->case_notes_deleted_at != null))
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