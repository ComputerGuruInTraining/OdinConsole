{{--@extends('layouts.master_layout')--}}
{{--@extends('sidebar')--}}
{{--@section('page-content')--}}
<section class="map">
<!-- Google Maps Javascript API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7xSpcb0ZqETybsCNdsyofP0Fmx_RurvQ&libraries=places"></script>
{{--TODO: check into pm &callback=initMap which I removed to allow auto-complete to work. Ramifications/Usage--}}
    <form>
        <input type="text" id="autocomplete" name="mapData"/>
        <input type="submit"/>
    </form>
{{--    <input type="submit" value="Submit" onclick="{{\App\Http\Controllers\LocationController::mapLocation($mapAddress)}}">--}}
    {{--<a href="{{route('LocationController@setAddress')}}">--}}
        {{--<button type="button">Submit</button></a>--}}
    <div id="map"></div>

    <script>
        var mapOptions = {
            center: new google.maps.LatLng(37.7831,-122.4039),
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById('map'), mapOptions);
        var acOptions = {
            types: ['geocode']
        };
        var autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'),acOptions);
        autocomplete.bindTo('bounds',map);
        var infoWindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
            map: map
        });

        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            infoWindow.close();
            var place = autocomplete.getPlace();
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            marker.setPosition(place.geometry.location);
            console.log(place);
            infoWindow.setContent('<div><strong>' + place.formatted_address + '</strong><br>');
            infoWindow.open(map, marker);
            google.maps.event.addListener(marker,'click',function(e){

                infoWindow.open(map, marker);

            });
//            var autoAddress = document.getElementById('autoAddress');
//            autoAddress.value = place.formatted_address;
{{--            {{\App\Http\Controllers\LocationController::mapLocation(place.formatted_address)}};--}}

        });
    </script>
</section>
{{--@stop--}}