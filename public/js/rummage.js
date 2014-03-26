// Google Map Scripts
function initializeMap() {
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
			zoom: 9,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
	DCDMAPGLOBAL.map = new google.maps.Map(document.getElementById("dcd-map-container"),myOptions);
	
	for(x in DCDMAPGLOBAL.points)
		createMarker(DCDMAPGLOBAL.points[x]);
}
function createMarker(point){
	//icons
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(point.lat,point.lon),
		map: DCDMAPGLOBAL.map,
		animation: google.maps.Animation.DROP,
		title: point.street
	});
	
	//info window
	var infowindow = new google.maps.InfoWindow({
		content: point.street + ", " + point.city + ", " + point.state + " " + point.zip
	});
	
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(DCDMAPGLOBAL.map,marker);
	});
}
// Google Maps Scripts

// Save Map Locations to visit
var locations = {
	selected : 0,
	list : new Array()
};
function visit(obj, id){
	//Credit to: Brian Cray
	//Found on: http://briancray.com/2009/09/30/remove-value-javascript-array/
	//
	//note: to support IE, you'll need this (thanks James!)
	if(!Array.prototype.indexOf){
		Array.prototype.indexOf = function(elt /*, from*/){
			var len = this.length >>> 0;
			
			var from = Number(arguments[1]) || 0;
			from = (from < 0) ? Math.ceil(from) : Math.floor(from);
			if (from < 0)
				from += len;
			
			for (; from < len; from++){
				if (from in this && this[from] === elt)
					return from;
			}
			return -1;
		};
	}
	
	if(obj.classList.contains("add") && locations.selected < 8){
		obj.classList.remove("add");
		obj.classList.add("remove");
		locations.selected++;
		locations.list.push(id);
		obj.value = "Remove";
		//document.getElementById('num-of-locations').innerHTML = locations.selected;
	}
	else if(obj.classList.contains("remove")){
		obj.classList.remove("remove");
		obj.classList.add("add");
		locations.selected--;
		locations.list.splice(locations.list.indexOf(id),1);
		obj.value = "Add";
		//document.getElementById('num-of-locations').innerHTML = locations.selected;
	}
	else{
		alert("Sorry! We can only map 8 items at a time");
	}
}
function mapRoute(){
	document.getElementById('locations').value = locations.list;
}

$(document).ready(function(){
	initializeMap();
});