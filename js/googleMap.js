$(document).ready( function(){
	$('#locationFinder, #addressOptions, .addressInput').hide();
	$('#locationCheck').click(function() {
		$('#locationFinder').toggle();
	});
	$('#locationCheck[type="button"]').click(function() {
		$('.editLocation').hide();
	});
});
var geocoder;
var map;
var markersArray = [];

function initialize() {
	var locationStart = new google.maps.LatLng(51.5073346, -0.12768310000001293);
	geocoder = new google.maps.Geocoder();
	var myOptions = {
		center: locationStart,
		zoom: 11,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	var marker = new google.maps.Marker({
		position: locationStart,
		map: map
	});
	markersArray.push(marker);
}

// Function to remove markers
function clearOverlays() {
	if(markersArray) {
		for (var i = 0; i < markersArray.length; i++) {
			markersArray[i].setMap(null);
		}
	}
}

// Function to search addresses and generate a list of the other options returned
function codeAddress(i) {
	clearOverlays();
	if (i === -1){
		var address = document.getElementById("address").value;
	}
	else {
		var address = document.getElementById("address" + i).innerHTML;
		$('.selected').removeClass();
		$('#address' + i).addClass("selected");
	};
    geocoder.geocode( {'address': address, 'region' : 'uk'},
	function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			var latDiv = document.getElementById("latitude");
			var lngDiv = document.getElementById("longitude");
			var addrDiv = document.getElementById("formatted_address");
			var lat = results[0].geometry.location.lat();
			var lng = results[0].geometry.location.lng();
			var addr = results[0].formatted_address;
			latDiv.value = lat;
			lngDiv.value = lng;
			addrDiv.value = addr;
			if (results.length > 1){
				$('.addressInput').show();
				var divTest = document.getElementById("results");
				divTest.innerHTML = "";
				$('#addressOptions').show();
				for (var i=0; i<results.length; i++) {
					divTest.innerHTML += "<tr><td id=\"address"+ i + "\" onclick=\"codeAddress(" + i + ")\" >" + results[i].formatted_address + "</td></tr>";
				};
				$('#address0').addClass("selected");
			}
			var marker = new google.maps.Marker({
				'map' : map,
				'position' : results[0].geometry.location
			});
			map.setCenter(results[0].geometry.location);
			map.fitBounds(results[0].geometry.viewport);
			markersArray.push(marker);
		}
		else {
			alert("Geocode was not successful for the following reason: " + status);
		}
    });
  }

