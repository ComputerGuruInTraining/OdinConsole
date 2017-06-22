<section>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7xSpcb0ZqETybsCNdsyofP0Fmx_RurvQ&libraries=places"></script>
    <div id="map-user">
    </div>


    <script>
        var map;
        var infoWindow;
        var markers = [];
        var locations = [
                @foreach ($locations as $location)
            [ {{ $location->latitude }}, {{ $location->longitude }} ],
            @endforeach
        ];

        google.maps.event.addDomListener(window, "load", function () {

            initMap();
            addMarker(locations);
//            showMarkers();
//            setMapOnAll(map);
//            createMarker();
//            setInterval();
        });

        function initMap() {
            map = new google.maps.Map(document.getElementById("map-user"), {
                center: new google.maps.LatLng(33.808678, -117.918921),
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            infoWindow = new google.maps.InfoWindow();
        }



        function addMarker(location) {
            for(var i = 0; i<location.length; i++){
                for(var j = 0; j <=i; j++){
                    var
                var marker = new google.maps.Marker({
                    position: location[0][0], location[0][1],
                    map: map
                });
                }
                markers.push(marker);
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


        setInterval(function() {
//            $.ajax({ url: '/my/site',
//                data: {action: 'test'},
//                type: 'post',
//                success: function(output) {
//                    // change the DOM with your new output info
//                }
//            });

        $.get('/dashboard', function(){
            console.log('locations');
            deleteMarkers();
            addMarker(locations);
//            createMarker();
        });
        }, 50000);

    </script>
</section>