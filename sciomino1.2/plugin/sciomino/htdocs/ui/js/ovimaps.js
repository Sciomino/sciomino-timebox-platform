jQuery(document).ready(function($) {

	if ($('#map').length > 0) {

		// our credentials
		nokia.Settings.set("app_id", "skfYn7bdNXHh3JPTJWUm"); 
		nokia.Settings.set("app_code", "pLt9imk66vqRbfJ9IJ9mNw");

		// create bubble
		var currentBubble = null;
		var infoBubbles = new nokia.maps.map.component.InfoBubbles();

		// init map
		var map = new nokia.maps.map.Display(
			document.getElementById("map"), {
				components: [ new nokia.maps.map.component.ZoomBar(), new nokia.maps.map.component.Behavior(), infoBubbles ],
				zoomLevel: 7,
				center: [52, 5.5]
			}
		);
		
		// get data
		$.ajax({
			url: MAP_JSON,
			dataType: 'json',
			success: function(data) {

				// create container
				container = new nokia.maps.map.Container();
				map.objects.add(container);
				
				// create markers
				for (var i = 0; i < data.locations.length; i++) {
					var marker = new nokia.maps.map.StandardMarker(
						[
							parseFloat(data.locations[i].geoLoc.Lat),
							parseFloat(data.locations[i].geoLoc.Lon)
						], {
							brush: "#6e9b1b",
							text: data.locations[i].nrPeople,
							draggable: false,
						}
					);

					// search with this location
					var cf_link = function(url, location) {
						return function() {
							url = url + location;
							window.location.href = url;
						}
					}

					// display name on mouseover in the bubble
					var cf_displayName = function(currentLocation) {
						return function() {
							//marker
							this.set('brush', "red");
							
							// bubble
							currentBubble = infoBubbles.openBubble("<div style='border:1px solid white;padding:3px 5px 3px 5px;background-color:red;color:white;font-weight:bold;'>" + currentLocation.location + "</div>", [ parseFloat(currentLocation.geoLoc.Lat) , parseFloat(currentLocation.geoLoc.Lon) ], "", true);
						}
					}
					// return to standard icon on mouseout
					var cf_displayPeople = function(currentLocation) {
						return function() {
							//marker
							this.set('brush', "#6e9b1b");
							
							// bubble
							if(currentBubble != null){
								infoBubbles.closeBubble(currentBubble);
								currentBubble = null;
							}				

						}
					}

					// urlStart is part of data and location name is part of location item
					marker.addListener('click', cf_link(data.urlStart, data.locations[i].location), false);
					
					// do something with the name of the location on mouseover/-out
					marker.addListener('mouseover', cf_displayName(data.locations[i]), false);
					marker.addListener('mouseout', cf_displayPeople(data.locations[i]), false);

					// add marker to container
					container.objects.add(marker);
					
				}
			}
		});	
	}


});
