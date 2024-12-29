<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
@ $db = new mysqli( 'localhost',  getenv('PHPUSER'),  getenv('PHPPWD'), 'Users');

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
   //first check if a provider is logged in
if (isset($_SESSION['provider_id'])) {
   //read in pos of driver
   $lat = $_POST["driverLat"];
   $lon = $_POST["driverLng"];
   $time = date("Y-m-d");
   //check if driver is in the active drivers list
   $driverId = $_SESSION['provider_id'];

   $selQuery = "SELECT * FROM driver_location WHERE id = ?"; //WHERE lat ...
   $statement = $db->prepare($selQuery);
   $statement -> bind_param("i", $driverId);

   $statement -> execute();

   $result = $statement -> get_result();

   $content = $result->fetch_all(MYSQLI_ASSOC);

   if($content){ //driver is in list; remove driver from list
      $selQuery = "DELETE FROM driver_location WHERE id = ?"; //WHERE lat ...
      $statement = $db->prepare($selQuery);
      $statement -> bind_param("i", $driverId);

      $statement -> execute();
      echo "NOTACTIVE";

   } else { //driver is not in list; add driver to list
      $free = "free";
      $selQuery = "insert into driver_location (`id`, `latitude`, `longitude`, `status`, `last_updated`) values (?,?,?,?,?)"; //WHERE lat ...
      $statement = $db->prepare($selQuery);
      $statement -> bind_param("iddss", $driverId, $lat, $lon, $free, $time);

      $statement -> execute();
      echo "ACTIVE";
   }

} else {
   //should not be possible
   echo "HOW IS NO PROVIDER SET";
}
}


?>