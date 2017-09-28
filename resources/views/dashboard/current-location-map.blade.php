<section>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7xSpcb0ZqETybsCNdsyofP0Fmx_RurvQ&libraries=places"></script>

    <div id="map-user"></div>

    <div style="padding:15px 0px 10px 0px;" class='form-group form-buttons'>
        {{--todo: info tip with the time interval --}}
        <button type="button" class="btn btn-primary padding-bottom" onclick="adjFreq()">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Increase GeoLocation Data Rate
        </button>
    </div>

    <script>
        var map;
        var infoWindow;
        var markers = [];
        var lat;
        var long;
        var positions = [];
        var interval;
        var frequency = 5000;//TODO: 60000 = 1 min for production
//        var jsCurrLocs = [];

        google.maps.event.addDomListener(window, "load", function () {

            currentPositions();
            initMap();
            setMarkers();
            showMarkers();

            interval = setInterval(function(){
                   updateMarkers();
                   console.log("interval call to update markers");
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

            //remove current markers
            deleteMarkers();

            //update function will retrieve values from api then update values in positions array
            update();

            setMarkers();
            showMarkers();
        }

        //used for initial positions gathered upon page load
        function currentPositions() {

            @foreach ($currentLocations as $location)
//TODO: use for storing all the $location values to then be used for info window
                //store the value in a js string which will then be parsed to an object
                //js variable is easier to use thoughout the fns, and allows storing of all the values in the location object
            var location = '{"address":"' + "<?php echo $location->address;?>" + '", "latitude":"' + "<?php echo $location->latitude;?>" + '"}';

                console.log(location);

                var posJs = JSON.parse(location);

                console.log(posJs.address);

                var position = {
                    latitude:{{ $location->latitude }}, longitude:{{  $location->longitude }}
                    {{--user_first_name:{{ $location->user_first_name }}, user_last_name:{{ $location->user_last_name }},--}}
                    {{--created_at:{{ $location->created_at }}, --}}
                };
//                console.log(position);
                positions.push(position);
            @endforeach
        }

        //assigns values to markers and info windows using positions array
        function setMarkers() {
            var iconDir = '{{ asset("/icons/marker.png") }}';

            for (i = 0; i < positions.length; i++) {
                var myLatlng = new google.maps.LatLng(positions[i].latitude, positions[i].longitude);

                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    icon: iconDir
                });

                //assign values to markers array
                markers.push(marker);

//                console.log(markers[i], positions[i]);
//
//                infoWindow(marker,  positions[i]);

            }
        }

        function infoWindow(marker, position)
        {
//            for (i = 0; i < markers.length; i++) {

                google.maps.event.addListener(marker, 'click', function () {
                    infoWindow.setContent("<h5>" + position.user_first_name + position.user_last_name + "</h5><p>" +
                    position.address + "<br>Time Stamp:" + position.created_at + "</p>");
                    infoWindow.open(map, this);
                });
//            }

        }

        //sets the markers on the map
        function setMapOnAll(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }

        //removes the markers from the map
        function clearMarkers() {
            setMapOnAll(null);
        }

        function showMarkers() {
            setMapOnAll(map);
        }

        //removes the markers from the map and removes the values assigned to markers
        function deleteMarkers() {
            clearMarkers();
            markers = [];
        }

        //allows the option to change the frequency of updating Markers as set in setInterval function
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

        //retrieve values from api
        function update(){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //clear the array holding the data of positions before assigning the response values to the array
                    this.positions = [];
console.log(this.responseText);
                    this.positions = JSON.parse(this.responseText);
                }
            };
            xhttp.open("GET", "http://odinlite.net/dashboard/64/current-positions", true);
            xhttp.send();
        }

    </script>
</section>

{{--//archived--}}

{{--<button type="button" onclick="adjFreq()"></button>--}}

{{--<a href="/dashboard">Reload Page</a><!--Not ideal, improve by getting from db and updating variable value-->--}}

{{--<button type="button" onclick="updateMarkers()">Get Updated Positions</button>--}}

{{--<p id="demo"></p>--}}

{{--//                    updatedPositions(updatedPos);--}}

{{--//--}}
{{--//        function updatedPositions(updatedPos){--}}
{{--//            //clear the array holding the data of positions before the update--}}
{{--//            positions = [];--}}
{{--////            console.log("latitude" + updatedPos[0].latitude);//262 for some reason--}}
{{--//            //add updated positions to the positions array--}}
{{--//            for (i = 0; i < updatedPos.length; i++) {--}}
{{--//                var position = {latitude: updatedPos[i].latitude, longitude: updatedPos[i].longitude};--}}
{{--//                console.log('position ' +  updatedPos[i].latitude);--}}
{{--//                positions.push(position);--}}
{{--//            }--}}
{{--//--}}
{{--//        }--}}

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