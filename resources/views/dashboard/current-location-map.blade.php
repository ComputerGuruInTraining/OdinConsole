<section>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7xSpcb0ZqETybsCNdsyofP0Fmx_RurvQ&libraries=places"></script>

    <div id="map-user"></div>

    <button type="button" onclick="adjFreq()">Adjust frequency of location gathering from 1 min to 10 sec</button>

    <a href="/dashboard">Reload Page</a><!--Not ideal, improve by getting from db and updating variable value-->

    <button type="button" onclick="update()">Get Updated Positions</button>

    <p id="demo"></p>

    <script>
        var map;
        var infoWindow;
        var markers = [];
        var lat;
        var long;
        var positions = [];
        var interval;
        var frequency = 5000;//TODO: 60000 = 1 min for production

        google.maps.event.addDomListener(window, "load", function () {

            currentPositions();
//            console.log('positions outside fn ' + positions[0].longitude);
            initMap();
            setMarkers();
            showMarkers();

            interval = setInterval(function(){
                   updateMarkers();
//                   console.log(frequency);

            }, frequency);

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
        }

        //remove current markers and set new markers with the values from the current locations array
        function updateMarkers(){
//            console.log('Frequency' + frequency);

            deleteMarkers();
            currentPositions();//atm, using the same data sent to the view each time
            setMarkers();
            showMarkers();

        }

        function currentPositions() {
            @foreach ($currentLocations as $location)

                var position = {latitude:{{ $location->latitude }}, longitude:{{  $location->longitude }}};
//                console.log('position ' + position.latitude);
                positions.push(position);

            @endforeach

        }

        function setMarkers() {
            var iconDir = '{{ asset("/icons/marker.png") }}';

            for (i = 0; i < positions.length; i++) {
//                console.log("Number of data rows returned " + positions.length);
                var myLatlng = new google.maps.LatLng(positions[i].latitude, positions[i].longitude);

                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    icon: iconDir
                });

                markers.push(marker);

                google.maps.event.addListener(marker, 'click', function () {
                    infoWindow.setContent("<h5> {{$location -> user_first_name}} {{$location ->user_last_name}}" +
                        "</h5><p>{{$location->address}} <br>Time Stamp: {{$location->created_at}}</p>");
                    infoWindow.open(map, this);
                });
            }
        }

        function setMapOnAll(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }

        function clearMarkers() {
            setMapOnAll(null);
        }

        function showMarkers() {
            setMapOnAll(map);
        }

        function deleteMarkers() {
            clearMarkers();
            markers = [];
        }

        function adjFreq(){
            frequency = 10000;

            //clear interval or else effectively an interval at 5sec and 10sec, although one might think the value would be replaced
            clearInterval(interval);

            //set the interval to be the new frequency
            interval = setInterval(function(){
                updateMarkers();
                console.log(frequency);

            }, frequency);
        }

        //http request to api FIXME: wip
        function update(){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("demo").innerHTML =
                        this.responseText;
                    console.log(this.responseText);
                }
            };
            xhttp.open("GET", "http://odinlite.net/dashboard/64/current-positions", true);
            xhttp.send();

        }

    </script>
</section>

{{--//archived--}}



{{--//            var myLatlng = new google.maps.LatLng(33.808678, -117.918921);--}}
{{--//--}}
{{--//            var marker = new google.maps.Marker({--}}
{{--//            position: myLatlng ,--}}
{{--//            map: map--}}
{{--//            });--}}
{{--//--}}
{{--//            var myLatlng = new google.maps.LatLng( 149.05922, -35.38027);--}}
{{--//--}}
{{--//            var marker = new google.maps.Marker({--}}
{{--//                position: myLatlng ,--}}
{{--//                map: map--}}
{{--//            });--}}

{{--//        var latLng = [];--}}
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


{{--//        setInterval(function() {--}}
{{--//            $.ajax({ url: '/my/site',--}}
{{--//                data: {action: 'test'},--}}
{{--//                type: 'post',--}}
{{--//                success: function(output) {--}}
{{--//                    // change the DOM with your new output info--}}
{{--//                }--}}
{{--//            });--}}

{{--//        $.get('/dashboard', function(){--}}
{{--//            console.log('locations');--}}
{{--//            deleteMarkers();--}}
{{--//            addMarker(locations);--}}
{{--////            createMarker();--}}
{{--//        });--}}
{{--//        }, 50000);--}}