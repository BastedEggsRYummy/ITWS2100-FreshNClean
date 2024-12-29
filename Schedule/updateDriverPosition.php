<?php
@ $db = new mysqli('localhost',  getenv('PHPUSER'),getenv('PHPPWD'),  'Users');
//@ $db = new mysqli(hostname: 'localhost', username: 'root', database: 'users'); 
if ($db->connect_error) {
   print(("error connecting to db"));
   $connectErrors = array(
     'errors' => true,
     'errno' => mysqli_connect_errno(),
     'error' => mysqli_connect_error()
   );
   echo json_encode($connectErrors);
} else {
session_start();
//first check if a provider is logged in; a driver has to be logged in to update their position
if (isset($_SESSION['provider_id'])) {
   $lat = $_POST["lat"];
   $lng = $_POST["lng"];
   $driverId = $_SESSION['provider_id'];

   //update driver's pos in activetrips
   $selQuery = "UPDATE activetrips SET driverLat = ?, driverLng = ? WHERE driverId = ?";
   $statement = $db->prepare($selQuery);
   $statement -> bind_param("ddi", $lat, $lng, $driverId);   
   $statement -> execute();

   $time = date("Y-m-d");

   //update driver's pos in driver_locations
   $selQuery = "UPDATE driver_location SET latitude = ?, longitude = ?, last_updated = ? WHERE id = ?";
   $statement = $db->prepare($selQuery);
   $statement -> bind_param("ddsi", $lat, $lng, $time, $driverId);   
   $statement -> execute();
   echo ("SUCCESS");

} else {
   echo ("no provider logged in!");
}
}


?>