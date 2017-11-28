<section class="map">
<!-- Google Maps Javascript API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7xSpcb0ZqETybsCNdsyofP0Fmx_RurvQ&libraries=places"></script>

{{--TODO: check into pm &callback=initMap which I removed to allow auto-complete to work. Ramifications/Usage--}}
    {{--If user inputs an address, and then selects a different address, need a catch or code to update the input field value--}}

    <div id="map"></div>

    {{ Form::label('address', 'Address *') }}

    <div class="alert alert-warning alert-custom">
        <strong>Important!</strong> Please include the country for accurate geoCoding
    </div>

    <input type="text" id="autocomplete" name="address" onkeypress="return noenter()"/>


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
        //for all addresses (although unable to notice much difference on quick inspection use following code instead.
        //however, concern that addresses may not be able to be converted to geoCode addresses, and therefore a catch will need to be added
        //if this is the case.
        //        var autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'));

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
                document.getElementById('autocomplete').value = place.formatted_address;
                console.log(place);

            });

        });

//        google.maps.event.addListener(marker, 'click')
    </script>
</section>
