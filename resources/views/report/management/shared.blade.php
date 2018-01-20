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

            <tr>
                <td></td>
                <td>{{$item->timeTzCheckIn}}</td>
                <td>{{$item->timeTzCheckOut}}</td>

                {{--action--}}
                @if($item->title == "Nothing to Report")
                    <td>Nothing to Report</td>
                    <td></td>
                @else
                    <td>Case Note Reported</td>
                    <td># {{$item->case_id}}</td>
                @endif

                {{--Guard ID--}}
                <td>{{$item->user}}</td>

                {{--Total Time--}}
                    @if(isset($item->checkDuration))
                        @if($item->checkDuration < 1)
                           <td> < 1</td>
                        @else
                            <td>{{$item->checkDuration}}</td>
                        @endif
                    @else
                        <td><i class="fa fa-minus" aria-hidden="true"></i></td>
                    @endif

                {{--GeoLocation--}}
                @if($item->withinRange == 'yes')
                    <td><i class="fa fa-check green-tick" aria-hidden="true"></i></td>
                @elseif($item->withinRange == 'ok')
                    <td><i class="fa fa-check orange-tick" aria-hidden="true"></i></td>
                @elseif($item->withinRange == 'no')
                    <td><i class="fa fa-times red-cross" aria-hidden="true"></i></td>
                @else
                    <td><i class="fa fa-minus" aria-hidden="true"></i></td>
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
    </tr>
@endif