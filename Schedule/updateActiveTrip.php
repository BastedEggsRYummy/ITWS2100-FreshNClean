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
//first check if a provider is logged in
if (isset($_SESSION['provider_id'])) {
   $status = $_POST["status"];

   $driverId = $_SESSION['provider_id'];

   //update driver's active trip with passed in status
   $selQuery = "UPDATE activetrips SET status = ? WHERE driverId = ?";
   $statement = $db->prepare($selQuery);
   $statement -> bind_param("si", $status,$driverId);   
   $statement -> execute();
   echo ("SUCCESS");

} else {
   echo ("no provider logged in!");
}
}


?>