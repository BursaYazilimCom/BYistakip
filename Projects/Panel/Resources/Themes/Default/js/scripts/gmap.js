      var latlng = new google.maps.LatLng(40.198773067436335, 29.06140324804689);
      var options = {
          zoom: 10,
          center: latlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          draggableCursor: "crosshair",
		  scrollwheel: true,
          streetViewControl: true
        };
      var map = new google.maps.Map(document.getElementById("map"), options);
      $("#zoom").val(5);
		  google.maps.event.addListener(map,"click", function(location)
      {
        GetLocationInfo(location.latLng);
      });
      google.maps.event.addListener(map,'zoom_changed', function(oldLevel, newLevel)
      {
        $("#zoom").val(map.getZoom());
      });
     var initListener;
      var marker;
    function GetLocationInfo(latlng)
      {
        if (latlng != null)
        {
          ShowLatLong(latlng);
         UpdateStreetView(latlng);
       }
      }
	  var searcBox = new google.maps.places.SearchBox(document.getElementById('sehir'));
 
	/*event ile değiştiğini algılıyoruz*/
	 
	google.maps.event.addListener(searcBox,'places_changed',function(){
	 
	var places = searcBox.getPlaces();
	var bounds = new google.maps.LatLngBounds();
	var place = "";
	//var ii ="";

	for (var ii = 0; place = places[ii]; ii++) {
	bounds.extend(place.geometry.location);
	}
	map.fitBounds(bounds);
	map.setZoom(15);
	 
	});
      function GotoLatLong()
      {
        if ($("#lat").val() != "" && $("#long").val() != "") {
         var lat = $("#lat").val();
          var long = $("#long").val();
          var latLong = new google.maps.LatLng(lat, long);
          ShowLatLong(latLong);
          map.setCenter(latLong);
          UpdateStreetView(latLong);
        }
      }


      function ShowLatLong(latLong)
      {
        // show the lat/long
        if (marker != null) {
          marker.setMap(null);
        }
        marker = new google.maps.Marker({
          position: latLong,
		  animation: google.maps.Animation.DROP,
          map: map});
        $("#lat").val(latLong.lat());
        $("#long").val(latLong.lng());
		$("#address").val((ReverseGeocode(latLong.lat(),latLong.lng(), '#address')));
		$("#adresX").val(ReverseGeocode(latLong.lat(),latLong.lng(), '#adresX'));
      }