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

                {{--location--}}
                <td>{{$item->address}}</td>

                {{--action--}}
                @if($item->title == "Nothing to Report")
                    <td class="min-width-lg">Nothing to Report</td>
                    <td></td>
                @else
                    <td>Case Note Reported</td>
                    <td># {{$item->case_id}}</td>
                @endif

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

                {{--GeoLocation uses check in geoLocation--}}
                @if($item->distance_check_in != null)
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