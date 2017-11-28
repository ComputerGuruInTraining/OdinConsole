<section class="map">
    <!-- Google Maps Javascript API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7xSpcb0ZqETybsCNdsyofP0Fmx_RurvQ&libraries=places"></script>

    {{--TODO: check into pm &callback=initMap which I removed to allow auto-complete to work. Ramifications/Usage--}}
    {{--If user inputs an address, and then selects a different address, need a catch or code to update the input field value--}}

    <div id="map"></div>

    {{ Form::label('address', 'Address *') }}

    <input type="text" id="autocomplete" name="address" disabled value="{{$address}}"/>

    <script>

        var lat = "<?php echo $lat;?>";

        var long = "<?php echo $long;?>";

        var locationCenter = new google.maps.LatLng(lat, long);

        var mapOptions = {
            center: locationCenter,
            zoom: 14,
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
            position: locationCenter,
            map: map,
            icon: iconDir
        });

        var address = "<?php echo $address;?>";

        google.maps.event.addListener(marker, 'click', function () {
            infoWindow.setContent("<h5>" + address + "</h5><p>" +
                position.address + "</p>");
            infoWindow.open(map, this);
        });

    </script>
</section>
