// updateLocation.js
function updateDriverLocation() {
  // Check if the provider is logged in

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude;
            var lon = position.coords.longitude;

            // Send the location data to the server via AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/resources/php/updateLocation.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr.responseText);  // Log server response (optional)
                }
            };
            xhr.send("lat=" + lat + "&lon=" + lon);
        });
    } else {
        console.log("Geolocation is not supported by this browser.");
    }
}


// Call updateDriverLocation every 60 seconds (60000 ms) only if the provider is logged in
setInterval(updateDriverLocation, 60000);

// Optionally, call it immediately when the provider logs in
updateDriverLocation();