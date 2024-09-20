<?php
include 'db.php';

// Fetch data from the places table
$query = "SELECT name, latitude, longitude FROM gas_stations";
$result = mysqli_query($conn, $query);

// Initialize an array to store the places data
$places = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $places[] = $row; // Append each place to the array
    }
} else {
    echo "No places found.";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Free Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
        #map { 
            height: 500px; width: 100%; 
        }

        a {
            float: right;
        }
    </style>
</head>
<body>
    <a href="home.php">go back</a>
    <h3>Maps</h3>
    <div id="map"></div>

    <script>
        // Initialize the map and set its view to a default location
        var map = L.map('map').setView([14.329444, 120.936667], 12); // Default view of Manila

        // Load and display OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Array of places fetched from PHP
        var places = <?php echo json_encode($places); ?>;

        // Add markers for each place
        places.forEach(function(place) {
            var marker = L.marker([place.latitude, place.longitude]).addTo(map);
            marker.bindPopup("<b>" + place.name + "</b><br>Latitude: " + place.latitude + "<br>Longitude: " + place.longitude);
        });

        // Function to calculate distance between two coordinates using Haversine formula
        function getDistance(lat1, lon1, lat2, lon2) {
            var R = 6371e3; // Radius of the Earth in meters
            var φ1 = lat1 * Math.PI / 180;
            var φ2 = lat2 * Math.PI / 180;
            var Δφ = (lat2 - lat1) * Math.PI / 180;
            var Δλ = (lon2 - lon1) * Math.PI / 180;

            var a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                    Math.cos(φ1) * Math.cos(φ2) *
                    Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            var d = R * c; // Distance in meters
            return d;
        }

        // Function to check if the user is near any location
        function ifNearStation(userLat, userLon) {
            places.forEach(function(place) {
                var distance = getDistance(userLat, userLon, place.latitude, place.longitude);
                
                // Check if the user is within 500 meters of a place
                if (distance <= 500) {
                    alert("You are near " + place.name + "!");
                }
            });
        }

        // Use the browser's geolocation API to get the user's location
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(function(position) {
                var userLat = position.coords.latitude;
                var userLon = position.coords.longitude;

                // Set the map view to the user's current location
                var userMarker = L.marker([userLat, userLon]).addTo(map).bindPopup("You are here").openPopup();
                map.setView([userLat, userLon], 13);

                // Check if the user is near any location
                ifNearStation(userLat, userLon);
            }, function() {
                alert("Unable to retrieve your location.");
            });
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    </script>
</body>
</html>
