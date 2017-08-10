<section>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7xSpcb0ZqETybsCNdsyofP0Fmx_RurvQ&libraries=places"></script>
    <div id="map-user">
    </div>

    <script>
        var map;
        var infoWindow;
        var markers = [];
        var latLng = [];
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

            initMap();
            addMarker();
//            showMarkers();
//            setMapOnAll(map);
//            createMarker();
//            setInterval();
        });

        function initMap() {
            map = new google.maps.Map(document.getElementById("map-user"), {
                center: new google.maps.LatLng(-35.3803372, 149.0590477),
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





        function addMarker() {
            {{--var iconDir = '{{ asset("/icons/marker.png") }}';--}}
           @foreach ($currentLocations as $location)
            var myLatlng = new google.maps.LatLng({{ $location->latitude }}, {{ $location->longitude }});

            var marker = new google.maps.Marker({
                position: myLatlng ,
                map: map
//                icon: iconDir
            });

            google.maps.event.addListener(marker, 'click', function () {
            infoWindow.setContent("<h3> {{$location -> user_first_name}} {{$location ->user_last_name}}" + "</h3><p>{{$location->address}} <br>Time Stamp: {{$location->updated_at}}</p>");
            infoWindow.open(map, this);
            });

            marker.setMap(map);
            @endforeach

        }

//        function setMapOnAll(map) {
//            for (var i = 0; i < markers.length; i++) {
//                markers[i].setMap(map);
//            }
//        }
//
//        function clearMarkers() {
//            setMapOnAll(null);
//        }
//
//        function showMarkers() {
//            setMapOnAll(map);
//        }
//
//        function deleteMarkers() {
//            clearMarkers();
//            markers = [];
//        }

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