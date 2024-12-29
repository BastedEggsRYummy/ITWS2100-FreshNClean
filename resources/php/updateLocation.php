<?php
session_start();

// Database configuration
$host = 'localhost';         
$user = getenv('PHPUSER');
$pass = getenv('PHPPWD');
$db_name = 'users'; 

// Create a connection
$mysqli = new mysqli($host, $user, $pass, $db_name);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if the driver is logged in and if the location data is received
if (isset($_SESSION['provider_id']) && isset($_POST['lat']) && isset($_POST['lon'])) {
    $provider_id = $_SESSION['provider_id'];  // Get the driver's ID from session
    $latitude = $_POST['lat'];
    $longitude = $_POST['lon'];

    // Prepare the SQL query to check if the driver's location exists
    $checkQuery = "SELECT COUNT(*) FROM driver_location WHERE id = ?";
    if ($checkStmt = $mysqli->prepare($checkQuery)) {
        $checkStmt->bind_param("i", $provider_id);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            // Entry exists, update the location
            $updateQuery = "UPDATE driver_location SET latitude = ?, longitude = ?, last_updated = NOW() WHERE id = ?";
            if ($updateStmt = $mysqli->prepare($updateQuery)) {
                $updateStmt->bind_param("ddi", $latitude, $longitude, $provider_id);
                $updateStmt->execute();
                if ($updateStmt->affected_rows > 0) {
                    echo "Location updated successfully";
                } else {
                    echo "Failed to update location";
                }
                $updateStmt->close();
            } else {
                echo "Failed to prepare update statement";
            }
        } else {
            // Entry does not exist, insert a new location
            $insertQuery = "INSERT INTO driver_location (id, latitude, longitude, status) VALUES (?, ?, ?, 'free')";
            if ($insertStmt = $mysqli->prepare($insertQuery)) {
                $insertStmt->bind_param("idd", $provider_id, $latitude, $longitude);
                $insertStmt->execute();
                if ($insertStmt->affected_rows > 0) {
                    echo "Location created successfully";
                } else {
                    echo "Failed to create location";
                }
                $insertStmt->close();
            } else {
                echo "Failed to prepare insert statement";
            }
        }
    } else {
        echo "Failed to prepare check statement";
    }
} else {
    echo "User not logged in or invalid data received";
}

// Close the database connection
$mysqli->close();
