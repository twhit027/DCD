// Google Map Scripts
function initializeMap() {
	var lat_avg = 0.0,
		lon_avg = 0.0,
		count = 0,
		no_showcase = true;
	
	for(x in DCDMAPGLOBAL.points){
		if(DCDMAPGLOBAL.points[x].showcase != true){
			lat_avg += parseFloat(DCDMAPGLOBAL.points[x].lat);
			lon_avg += parseFloat(DCDMAPGLOBAL.points[x].lon);
			count++;
		}
		else{
			lat_avg = DCDMAPGLOBAL.points[x].lat;
			lon_avg = DCDMAPGLOBAL.points[x].lon;
			no_showcase = false;
			break;
		}
	}
	if(no_showcase){
		if(lat_avg != 0.0)
			lat_avg = lat_avg/count;
		else
			lat_avg = 38.93206;
		if(lon_avg != 0)
			lon_avg = lon_avg/count;
		else
			lon_avg = -77.2191306;
	}
	
	var latlng = new google.maps.LatLng(lat_avg,lon_avg),
		myOptions = {
			zoom: 9,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
	DCDMAPGLOBAL.map = new google.maps.Map(document.getElementById("dcd-map-container"),myOptions);
	DCDMAPGLOBAL.markersArray = new Array();
	
	for(x in DCDMAPGLOBAL.points)
		createMarker(DCDMAPGLOBAL.points[x],x);
	
	$("body").on('click', '.custom-infowindow a', function(e) { e.preventDefault(); visitMap($(this),$(this).data("id"));  });
}
function createMarker(point,id){
	
	//icons & info window
	var json = { 
		marker : new google.maps.Marker({
			position: new google.maps.LatLng(point.lat,point.lon),
			map: DCDMAPGLOBAL.map,
			animation: google.maps.Animation.DROP,
			title: point.street,
			icon: 'img/map.png'
		}),
		infoWindow :  new google.maps.InfoWindow({
			content: "<p class='custom-infowindow'>" + point.street + ", " + point.city + ", " + point.state + " " + point.zip + "<br><a href='#' data-id='" + id + "'>Add to route</a></p>"
		})
	};
	
	DCDMAPGLOBAL.markersArray[id] = json;
	
	google.maps.event.addListener(json.marker, 'click', function() {
		json.infoWindow.open(DCDMAPGLOBAL.map,json.marker);
	});
	
	if(point.showcase == true){
		json.infoWindow.open(DCDMAPGLOBAL.map,json.marker);
	}
}
// Google Maps Scripts

// Save Map Locations to visit
var locations = {
	selected : 0,
	list : new Array()
};
function visitMap(_this, _id){
	if(visit($('#'+_id).get(0),_id) == true)
		_this.html("Remove from route");
	else
		_this.html("Add to route");
}
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
	
	var bool = false;
	if(obj.classList.contains("add") && locations.selected < 8){
		obj.classList.remove("add");
		obj.classList.add("remove");
		locations.selected++;
		locations.list.push(id);
		obj.value = "Remove";
		//document.getElementById('num-of-locations').innerHTML = locations.selected;
		bool = true;
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
	if(locations.selected > 0){
		updateButton('moreThanOneRoute',true);
	}
	else{
		updateButton('moreThanOneRoute',false);
	}
	return bool;
}
function mapRoute(){
	document.getElementById('locations').value = locations.list;
}
var allowDirections = {
	moreThanOneRoute : false,
	address : false,
	city : false,
	zip : false
}
function updateButton (value, toggle){
	if(value == "moreThanOneRoute"){
		allowDirections.moreThanOneRoute = toggle;
	}
	else if(value == "address"){
		allowDirections.address = toggle;
	}
	else if(value == "city"){
		allowDirections.city = toggle;
	}
	else if(value == "zip"){
		allowDirections.zip = toggle;
	}
	
	if(allowDirections.moreThanOneRoute && allowDirections.address && allowDirections.city && allowDirections.zip){
		$("#dcd-route").removeAttr("disabled");
	}
	/*if(allowDirections.moreThanOneRoute){
		$("#dcd-route").removeAttr("disabled");
	}*/
	else{
		$("#dcd-route").attr("disabled","disabled");
	}
}
function toggleState (_this){
	if(!_this.val()){
		updateButton(_this.attr('name'),false);
	}
	else{
		updateButton(_this.attr('name'),true);
	}
}
function showMarker (_this){
	closeMarkers();
	//_this = $(this);
	var _id = _this.attr('dcd-id');
	google.maps.event.trigger(DCDMAPGLOBAL.markersArray[_id].marker,'click');
}
function closeMarkers (){
	for(x in DCDMAPGLOBAL.markersArray)
		DCDMAPGLOBAL.markersArray[x].infoWindow.close();
}

$(document).ready(function(){
	initializeMap();
	$("#dcd-route").attr("disabled","disabled");
	$(".form-control").change(function() { toggleState($(this)); });
	$(".dcd-adText").click(function() { showMarker($(this)); });
	$("#map-resize a").click(function(e) { e.preventDefault(); mapsize($(this).data("size")); });
});

function mapsize(_size){
	switch(_size){
		case 'small':
			$('#dcd-map-container').css("height","175px");
			break;
		case 'medium':
			$('#dcd-map-container').css("height","350px");
			break;
		case 'large':
			$('#dcd-map-container').css("height","700px");
			break;
		default:
	}
	google.maps.event.trigger(DCDMAPGLOBAL.map, "resize");
}

function setGetParameter(paramName, paramValue) {
    var url = window.location.href;
	if (url.indexOf("?") < 0) {
		url += "?" + paramName + "=" + encodeURIComponent(paramValue);
	} else {
		url += "&" + paramName + "=" + encodeURIComponent(paramValue);
	}
    window.location.href = url;
    return false;
}

function removeSitesAndReloadPage(paramName) {
	var url = window.location.href;
	if(paramName == 'city')
		url = url.replace(/&?city=([^&]$|[^&]*)/i, "");
	if(paramName == 'paper')
		url = url.replace(/&?paper=([^&]$|[^&]*)/i, "");
	window.location.href = url;
	return false;
}