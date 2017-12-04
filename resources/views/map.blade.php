<section class="map">
<!-- Google Maps Javascript API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7xSpcb0ZqETybsCNdsyofP0Fmx_RurvQ&libraries=places"></script>

{{--TODO: check into pm &callback=initMap which I removed to allow auto-complete to work. Ramifications/Usage--}}
    {{--If user inputs an address, and then selects a different address, need a catch or code to update the input field value--}}

    <div id="map"></div>

    <br>

    @if(!isset($show))
        {{ Form::label('address', 'Address *') }}

        {{--FIXME: pressing enter still causes submit btn event to fire, and error msg is displayed to user on create page thankfully input saved --}}

        @if(isset($location->address))
            {{--edit locations page--}}

            <input type="text" id="autocomplete" name="address" value="{{ old('address') ? old('address') : $location->address}}" onkeypress="return noenter()"/>

        @elseif(isset($addressConfirm))
            {{--create locations page routed to via back button on Confirm Location Page, therefore, with values in the fields--}}

            <input type="text" id="autocomplete" name="address" value="{{$addressConfirm}}" onkeypress="return noenter()"/>

        @else
            {{--create locations page--}}

            <input type="text" id="autocomplete" name="address" value="{{ old('address')}}" onkeypress="return noenter()"/>
        @endif

    @endif
    <script>

                @if(isset($location->latitude))
                    var lat = "<?php echo $location->latitude;?>";
                    var long = "<?php echo $location->longitude;?>";
                    var address = "<?php echo $location->address;?>";
                    var zoom = 17;
                @else
                    var lat = 37.7831;
                    var long = -122.4039;
                    var zoom = 3;
                @endif

        var locationCenter = new google.maps.LatLng(lat, long);

        var mapOptions = {
            center: locationCenter,
            zoom: zoom,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById('map'), mapOptions);
        var acOptions = {
            types: ['geocode']
        };
        var autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'),acOptions);
        //for all addresses (although unable to notice much difference on quick inspection use following code instead.
        //however, concern that addresses may not be able to be converted to geoCode addresses, and therefore a catch will need to be added
        //if this is the case.
        //        var autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'));

        autocomplete.bindTo('bounds',map);
        var infoWindow = new google.maps.InfoWindow();
        var iconDir = '{{ asset("/icons/marker.png") }}';

        var marker = new google.maps.Marker({
            map: map,
            icon: iconDir
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
                document.getElementById('autocomplete').value = place.formatted_address;
                console.log(place);

            });

        });

        @if(isset($location->latitude))

                var myMarker = new google.maps.Marker({
                    position: locationCenter,
                    map: map,
                    icon: iconDir
                });

                google.maps.event.addListener(myMarker, 'click', function () {
                    infoWindow.setContent("<h5>" + address + "</h5>");
                    infoWindow.open(map, this);
                });
        @endif

    </script>
</section>
