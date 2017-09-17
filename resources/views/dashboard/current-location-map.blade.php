<section>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7xSpcb0ZqETybsCNdsyofP0Fmx_RurvQ&libraries=places"></script>
    <button type="button" onclick="updateMarkers()">Update Locations</button>
    <div id="map-user"></div>

    <script>
        var map;
        var infoWindow;
        var markers = [];
        var lat;
        var long;
        var positions = [];
        //        var latLng = [];
        {{--var locations = [--}}
                {{--@foreach ($currentLocations as $location)--}}
            {{--[ {{ $location->latitude }}, {{ $location->longitude }} ],--}}
            {{--@endforeach--}}
        {{--];--}}

        {{--function getCurrentLocation(){--}}
            {{--var lat = 0 ;--}}
            {{--var lng= 0 ;--}}

                {{--@foreach ($currentLocations as $location)--}}
             {{--lat = {{ $location->latitude }};--}}
             {{--lng =  {{ $location->longitude }} ;--}}
{{--//            console.log(lat);--}}
            {{--latLng = [new google.maps.LatLng(lat, lng)];--}}
{{--//            console.log(latLng);--}}
            {{--@endforeach--}}
{{--//            console.log(latLng);--}}
            {{--return latLng;--}}
        {{--}--}}

        google.maps.event.addDomListener(window, "load", function () {

            currentPositions();
            console.log('positions outside fn ' + positions[20].longitude);
            initMap();
            setMarkers();
            showMarkers();

            //nb fns called within setTimeout not working
//            setTimeout(function(){
//console.log('positions outside fn '+ positions[20].longitude);
//                initMap();


//            }, 50000);

//
//            var intervalID = setInterval(function(){
//                    deleteMarkers();
//                    setMarkers();
//                    showMarkers();
//
//            }, 10000);


//            showMarkers();
//            setMapOnAll(map);
//            createMarker();
//            setInterval();
        });

        function initMap() {

            lat = positions[0].latitude;
            long = positions[0].longitude;

            map = new google.maps.Map(document.getElementById("map-user"), {

                center: new google.maps.LatLng(lat, long),
                zoom: 15,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            infoWindow = new google.maps.InfoWindow();

//            var myLatlng = new google.maps.LatLng(33.808678, -117.918921);
//
//            var marker = new google.maps.Marker({
//            position: myLatlng ,
//            map: map
//            });
//
//            var myLatlng = new google.maps.LatLng( 149.05922, -35.38027);
//
//            var marker = new google.maps.Marker({
//                position: myLatlng ,
//                map: map
//            });

        }

        //remove current markers and set new markers with the values from the current locations array
        function updateMarkers(){

            deleteMarkers();
            currentPositions();
            setMarkers();
            showMarkers();

        }

        function currentPositions() {
            @foreach ($currentLocations as $location)

                var position = {latitude:{{ $location->latitude }}, longitude:{{  $location->longitude }}};
                console.log('position ' + position.latitude);
                positions.push(position);

            @endforeach

        }

        function setMarkers() {
                    {{--var iconDir = '{{ asset("/icons/marker.png") }}';--}}

            for (i = 0; i < positions.length; i++) {
                var myLatlng = new google.maps.LatLng(positions[i].latitude, positions[i].longitude);

                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map
//                icon: iconDir
                });

                markers.push(marker);

                google.maps.event.addListener(marker, 'click', function () {
                    infoWindow.setContent("<h3> {{$location -> user_first_name}} {{$location ->user_last_name}}" + "</h3><p>{{$location->address}} <br>Time Stamp: {{$location->updated_at}}</p>");
                    infoWindow.open(map, this);
                });

//            marker.setMap(map);
            }
            //            console.log(markers);

        }


        function setMapOnAll(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }
        ////
        function clearMarkers() {
            setMapOnAll(null);
        }
        //
        function showMarkers() {
            setMapOnAll(map);
        }
        //
        function deleteMarkers() {
            clearMarkers();
            markers = [];
        }

        {{--function createMarker() {--}}
        {{--@foreach ($locations as $location)--}}
        {{--lnag = {{$location->longitude}};--}}
        {{--latt = {{$location->latitude}};--}}
        {{--var posi = {lat: lnag, lng: 'latt'};--}}
        {{--var marker = new google.maps.Marker({--}}
        {{--position: {--}}
        {{--lat: {{$location->latitude}},--}}
        {{--lng: {{$location->longitude}}--}}
        {{--},--}}
        {{--map: map,--}}
        {{--title: '{{$location -> name}}'--}}
        {{--});--}}

        {{--google.maps.event.addListener(marker, 'click', function () {--}}
        {{--infoWindow.setContent("<h3> {{$location -> name}}" + "</h3><p>{{$location->address}}</p>");--}}
        {{--infoWindow.open(map, this);--}}
        {{--});--}}
        {{--@endforeach--}}
        {{--}--}}


        //        setInterval(function() {
        //            $.ajax({ url: '/my/site',
        //                data: {action: 'test'},
        //                type: 'post',
        //                success: function(output) {
        //                    // change the DOM with your new output info
        //                }
        //            });

        //        $.get('/dashboard', function(){
        //            console.log('locations');
        //            deleteMarkers();
        //            addMarker(locations);
        ////            createMarker();
        //        });
        //        }, 50000);

    </script>
</section>