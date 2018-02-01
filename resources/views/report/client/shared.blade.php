{{--File used by both client view and pdf--}}

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
            @if(isset($edit))
                <td></td>
            @endif

        </tr>

        @foreach ($data->get($index) as $item)

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
                @endif

                {{--Image--}}

                @if($item->hasImg == "Y")
                    <td>Yes</td>
                @else
                    <td></td>
                @endif

                {{--Edit btns for edit view only--}}
                @if(isset($edit))
                    @if($item->title != "Nothing to Report")

                        <td><a href="/edit-case-notes/{{$item->case_note_id}}/reports/{{$report->id}}" class="edit-links"><i class="fa fa-edit"></i></a>

                            <a href="/delete/{{$urlCancel}}/{{$item->case_note_id}}/{{$report->id}}" style="color: #990000;"><i class="fa fa-trash-o icon-padding"></i></a>
                        </td>
                    @else
                        <td>
                            <a href="/delete/{{$urlCancel}}/{{$item->case_note_id}}/{{$report->id}}" style="color: #990000;"><i class="fa fa-trash-o"></i></a>
                        </td>
                    @endif
                @endif
            </tr>
        @endforeach
        </tbody>
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