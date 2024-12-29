<?php
@ $db = new mysqli( 'localhost',  getenv('PHPUSER'), getenv('PHPPWD'),  'Users');
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
   //read in formdata vars
   $userId = $_POST["userId"];
   $driverId = $_POST["driverId"];
   $userLat = $_POST["userLat"];
   $userLng = $_POST["userLng"];
   $driverLat = $_POST["driverLat"];
   $driverLng = $_POST["driverLng"];
   $laundromatLat = $_POST["laundromatLat"];
   $laundromatLng = $_POST["laundromatLng"];
   $userName = $_POST["userName"];
   $driverName = $_POST["driverName"];
   $laundromatName = $_POST["laundromatName"];
   $instructions = $_POST["instructions"];
   $status = "pickup";
    //check if user already has an active trip
    $selQuery = "SELECT * FROM activetrips WHERE userid = ?";
    $statement = $db->prepare($selQuery);
    $statement -> bind_param("i", $userId);
    $statement -> execute();
    $result = $statement -> get_result();
    $content = $result->fetch_all(MYSQLI_ASSOC);
 
    if($content){ //if an active trip was found
       echo "ALREADYHASTRIP";
    } else {
      $insQuery = "insert into activetrips (`userId`, `driverId`, `userLat`, `userLng`, `driverLat`, `driverLng`, `laundromatLat`, `laundromatLng`, `userName`, `driverName`, `laundromatName`, `instructions`, `status`) values(?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $statement = $db->prepare($insQuery);
      $statement -> bind_param("iiddddddsssss", $userId, $driverId, $userLat, $userLng, $driverLat, $driverLng, $laundromatLat, $laundromatLng, $userName, $driverName, $laundromatName, $instructions, $status);
      $statement->execute();
   
      $statement->close();

      $intrip = "booked";
      //now change status in driver_location from 'free' to 'in_trip'
      $selQuery = "UPDATE driver_location SET status = ? WHERE id = ?"; 
      $statement = $db->prepare($selQuery);
      $statement -> bind_param("si", $intrip,$driverId);   
      $statement -> execute();

      echo "SUCCESS";
    }



 }


?>