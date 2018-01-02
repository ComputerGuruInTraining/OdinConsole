<section>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7xSpcb0ZqETybsCNdsyofP0Fmx_RurvQ&libraries=places"></script>

    <div id="map-user"></div>

    <div style="padding:15px 0px 10px 0px;" class='form-group form-buttons'>
        <button type="button" class="btn btn-primary padding-bottom" data-container="body" data-toggle="popover" data-placement="top"
                data-content="Change GeoLocation Gather Rate from every 5 min to every 30 seconds" onclick="adjFreq()">
            <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span> GeoLocation Rate
        </button>
        <button type="button" id="info" class="padding-bottom" clear data-container="body" data-toggle="popover" data-placement="top"
                data-content="Change from every 5m to every 30s" onclick="info()"
                style="color:#3c8dbc; border: none; background-color: transparent;">
            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
        </button>
    </div>

    <script>
        var map;
        var markers = [];
        var lat;
        var long;
        var positions = [];
        var interval;
        var frequency = 300000;//5 mins
        var zoom = 11;//default value

        google.maps.event.addDomListener(window, "load", function () {

            //used for the initial rendering of the map including the center of the map
            currentPositions();
            mapCenter();
            initMap(zoom);

            if (positions.length > 0){
                setMarkers();//and info windows
                showMarkers();
            }
            //update Markers is used for continuous data gathering and marker setting
            interval = setInterval(function(){
                   updateMarkers();
            }, frequency);

        });

        function mapCenter(){

            //store in a JS variable the location details in the $center variable passed from controller
            var locationCo = '{' +'"address":"' + "<?php echo $center->address;?>" +
                '", ' +
                '"latitude":"' + "<?php echo $center->latitude;?>" +
                '", ' + '"longitude":"' + "<?php echo $center->longitude;?>" + '"}';

            var center = JSON.parse(locationCo);
            //determine and assign center of map
            if(positions.length > 0) {
                lat = positions[0].latitude;
                long = positions[0].longitude;
            }else{
                lat = center.latitude;
                long = center.longitude;
                zoom = 3;
            }
        }

        function initMap(zoom) {

            map = new google.maps.Map(document.getElementById("map-user"), {

                center: new google.maps.LatLng(lat, long),
                zoom: zoom,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
        }

        //remove current markers and set new markers with the values from the current locations array
        function updateMarkers(){

            console.log("updating markers...");
            //remove current markers
            deleteMarkers();

            //update function will retrieve values from api then update values in positions array
            update();

            if(positions.length > 0) {
                setMarkers();
                showMarkers();
            }
        }

        //used for initial positions gathered upon page load
        function currentPositions() {
            //if there are no items in the $current_locations ie no geoData for mobile users for the period
            @if(count($currentLocations) > 0)

                @foreach ($currentLocations as $location)
                    //store the value in a js string which will then be parsed to an object
                    //js variable is easier to use thoughout the fns, and allows storing of all the values in the location object
                var location = '{"address":"' + "<?php echo $location->address;?>" +
                    '", ' + '"latitude":"' + "<?php echo $location->latitude;?>" +
                    '", ' + '"longitude":"' + "<?php echo $location->longitude;?>" +
                    '", ' + '"user_first_name":"' + "<?php echo $location->user_first_name;?>" +
                    '", ' + '"user_last_name":"' + "<?php echo $location->user_last_name;?>" +
                    '", ' + '"created_at":"' + "<?php echo $location->created_at;?>" + '"}';

                    var posJs = JSON.parse(location);

                    positions.push(posJs);
                @endforeach
            @endif
        }

        //assigns values to markers and info windows using positions array
        function setMarkers() {
            var iconDir = '{{ asset("/icons/marker.png") }}';

            for (i = 0; i < positions.length; i++) {
                var myLatlng = new google.maps.LatLng(positions[i].latitude, positions[i].longitude);

                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    icon: iconDir,
                });


                //assign values to markers array
                markers.push(marker);

                setInfoWindow(marker,  positions[i], positions[i].latitude, positions[i].longitude);

            }
        }

        function setInfoWindow(marker, position, lat, long)
        {
            /*Gathering accurate date and time and formatting*/
            //convert date to current timezone and user-friendly date and time

            var timestamp = (ts(position.created_at))/1000;

            //use google api to convert the timestamp to the user's timezone using the latitude and location of the geoLocation data
            $.get( 'https://maps.googleapis.com/maps/api/timezone/json?location=' + lat + ',' + long +
                '&timestamp=' + timestamp + '&key=AIzaSyBbSWmsBgv_YTUxYikKaLTQGf5r4n0o-9I', function(result){

                var dateForTZ = timestamp + result.dstOffset + result.rawOffset;

                //convert timestamp for timezone into a user-friendly date and time
                var d = new Date(dateForTZ*1000);

                //format to date
                var date = dateStr(d);

                //format to time
                var time = timeStr(d);

                //user-friendly string for displaying on view
                var dtTzString = date + ' ' + time;

                //info window that shows upon marker click
                var infoWindow = new google.maps.InfoWindow();

                {{--infoWindow.setContent("<h5>" + position.user_first_name + " " + position.user_last_name + " @ " + dtTzString + "</h5><p>" +--}}
                    {{--position.address + "</p>");--}}

                infoWindow.setContent("<h5>" + position.user_first_name + " " + position.user_last_name + " @ " + dtTzString + "</h5>");

                //open upon marker click
                google.maps.event.addListener(marker, 'click', function () {

                    infoWindow.open(map, marker);

                });

            });
        }

        //pass in a date time string and create a date object in UTC time, then convert to a timestamp
        function ts(dtStr){
            //extract values from string
            var y = dtStr.substr(0, 4);

            var m = (dtStr.substr(5, 2)-1);

            var d = dtStr.substr(8, 2);

            var h = dtStr.substr(11, 2);

            var i = dtStr.substr(14, 2);

            var s = dtStr.substr(17, 2);

            //convert string to date object
            var dtObj = new Date(Date.UTC(y, m, d, h, i, s));

            var tStamp = dtObj.getTime();

            return tStamp;
        }

        // returns a formatted time string
        function timeStr(tm){
            var ampm = "am";
            var min;
            var hours = tm.getUTCHours();
            var h = hours;
            var m = tm.getUTCMinutes();

            if(m > 0){
                min = ":" + ("0" + m).slice(-2);   // Add leading 0.
            }
            else if(m == 0){
                min = '';
            }

            //format hours
            if (hours > 12) {
                h = hours - 12;
                ampm = 'pm';
            } else if (hours === 12) {
                h = 12;
                ampm = 'pm';
            } else if (hours == 0) {
                h = 12;
            }

            var fTime = h + min + ampm;

            return fTime;
        }

        function monthName(tm){
            var monthNames = ["Jan", "Feb", "March", "Apr", "May", "June",
                "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
            var mn = monthNames[tm.getUTCMonth()];
            return mn;

        }

        // returns a formatted date string dd mmm (eg 11 Oct)
        function dateStr(tm){

            var mn = this.monthName(tm);
            // var yyyy = tm.getUTCFullYear();
            var dd = ("0" + tm.getUTCDate()).slice(-2);// Add leading 0.

            var fDate = dd + ' ' + mn;
            return fDate;
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
            frequency = 30000;

            //clear interval or else effectively an interval at 5sec and 10sec, although one might think the value would be replaced
            clearInterval(interval);

            //set the interval to be the new frequency
            interval = setInterval(function(){
                updateMarkers();

            }, frequency);
        }

        //retrieve values from api
        function update(){
            var xhttp = new XMLHttpRequest();
            var companyId = "<?php echo $company->id;?>";
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //clear the array holding the data of positions before assigning the response values to the array
                    this.positions = [];
                    this.positions = JSON.parse(this.responseText);
                }
            };
            console.log(companyId);
            xhttp.open("GET", "http://odinlite.net/dashboard/"+ companyId +"/current-positions", true);
            xhttp.send();
        }

        function info(){
            $('#info').popover('toggle')

        }

    </script>
</section>