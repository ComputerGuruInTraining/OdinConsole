{{--Check to ensure there are case notes or else an error will be thrown--}}
@if(count($data) != 0)
    @foreach($data as $index => $shiftCheck)

        <tbody class="group-list">

        <tr>
            <td class="report-title">{{$index}}</td>
            <td></td>
            <td></td>
            <td></td>
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