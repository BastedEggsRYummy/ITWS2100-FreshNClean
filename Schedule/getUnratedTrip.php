<?php
@ $db = new mysqli( 'localhost',  getenv('PHPUSER'),  getenv('PHPPWD'),  'Users');
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
   //handle if user or driver is logged in
   if (isset($_SESSION['provider_id'])) {
      //driver case
      $driverId = $_SESSION['provider_id'];
      $selQuery = "SELECT * FROM history WHERE provider_id = ? AND ratingOfUser = 0 AND result = 'finished'";
      $statement = $db->prepare($selQuery);
      $statement -> bind_param("i", $driverId);
      $statement -> execute();
      $result = $statement -> get_result();
      $content = $result->fetch_all(MYSQLI_ASSOC);
      if($content){
         //unrated trip found, retrieve info about the user to display with the trip
         $selQuery = "SELECT * FROM users WHERE id = ? ";
         $statement = $db->prepare($selQuery);
         $statement -> bind_param("i", $content[0]["user_id"]);
         $statement -> execute();
         $result = $statement -> get_result();
         $usercontent = $result->fetch_all(MYSQLI_ASSOC);
         $content[0]["otherName"] = $usercontent[0]["first_name"];
         $content[0]["otherProfile"] = $usercontent[0]["profile_picture"];

         echo json_encode($content[0]);
      } else {
         echo json_encode("NONE");
      }
   } else {
      //user case
      $userId = $_SESSION['user_id'];
      $selQuery = "SELECT * FROM history WHERE user_id = ? AND ratingOfDriver = 0 AND result = 'finished'";
      $statement = $db->prepare($selQuery);
      $statement -> bind_param("i", $userId);
      $statement -> execute();
      $result = $statement -> get_result();
      $content = $result->fetch_all(MYSQLI_ASSOC);
      if($content){
         //unrated trip found, retrieve info about the driver to display with the trip
         $selQuery = "SELECT * FROM providers WHERE id = ? ";
         $statement = $db->prepare($selQuery);
         $statement -> bind_param("i", $content[0]["provider_id"]);
         $statement -> execute();
         $result = $statement -> get_result();
         $drivercontent = $result->fetch_all(MYSQLI_ASSOC);
         $content[0]["otherName"] = $drivercontent[0]["first_name_or_business_name"];
         $content[0]["otherProfile"] = $drivercontent[0]["profile_picture"];

         echo json_encode($content[0]);
      } else {
         echo json_encode("NONE");
      }
   }

 }
 ?>
