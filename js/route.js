function showDirections() {
	var lat_avg = 0.0,
		lon_avg = 0.0,
		count = 0;
	
	for(x in DCDMAPGLOBAL.points){
		lat_avg += parseFloat(DCDMAPGLOBAL.points[x].lat);
		lon_avg += parseFloat(DCDMAPGLOBAL.points[x].lon);
		count++;
	}
	if(lat_avg != 0.0)
		lat_avg = lat_avg/count;
	else
		lat_avg = 38.93206;
	if(lon_avg != 0)
		lon_avg = lon_avg/count;
	else
		lon_avg = -77.2191306;
	
	var latlng = new google.maps.LatLng(lat_avg,lon_avg),
		myOptions = {
			zoom: 10,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
	DCDMAPGLOBAL.map = new google.maps.Map(document.getElementById("direction-canvas"),myOptions);
	DCDMAPGLOBAL.directionsDisplay = new google.maps.DirectionsRenderer();
	DCDMAPGLOBAL.directionsDisplay.setMap(DCDMAPGLOBAL.map);
	DCDMAPGLOBAL.directionsDisplay.setPanel(document.getElementById('directions-panel'));
	DCDMAPGLOBAL.directionsService = new google.maps.DirectionsService();
	
	var address = '',
		waypts = [],
		needOrigin = false;
	
	if(DCDMAPGLOBAL.address.street == ''){
		needOrigin = true;
	}
	else{
		address = DCDMAPGLOBAL.address.street+", "+DCDMAPGLOBAL.address.city+" "+DCDMAPGLOBAL.address.zip
	}
	
	for(x in DCDMAPGLOBAL.points){
		if(needOrigin){
			address = DCDMAPGLOBAL.points[x].street+", "+DCDMAPGLOBAL.points[x].city+" "+DCDMAPGLOBAL.points[x].state+" "+DCDMAPGLOBAL.points[x].zip;
			needOrigin = false;
		}
		else{
			waypts.push({
					location: DCDMAPGLOBAL.points[x].street+", "+DCDMAPGLOBAL.points[x].city+" "+DCDMAPGLOBAL.points[x].state+" "+DCDMAPGLOBAL.points[x].zip
				});
		}
	}
		
	var request = {
		origin: address,
		destination: address,
		waypoints: waypts,
		optimizeWaypoints: true,
		travelMode: google.maps.TravelMode.DRIVING
	};
	if(DCDMAPGLOBAL.avoidHighways == true)
		request.avoidHighways = true;
	if(DCDMAPGLOBAL.avoidTolls == true)
		request.avoidTolls = true;
	DCDMAPGLOBAL.directionsService.route(request, function(response, status){
		if (status == google.maps.DirectionsStatus.OK){
			DCDMAPGLOBAL.directionsDisplay.setDirections(response);
		}
	});
}

$(document).ready(function(){
	showDirections();
});