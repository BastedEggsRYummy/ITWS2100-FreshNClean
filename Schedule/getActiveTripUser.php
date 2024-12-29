<?php
@ $db = new mysqli( 'localhost', getenv('PHPUSER'), getenv('PHPPWD'),  'Users');
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
   //read in id from session
   session_start();
   $userId = $_SESSION['user_id'];

   $selQuery = "SELECT * FROM activetrips WHERE userid = ?";
   $statement = $db->prepare($selQuery);
   $statement -> bind_param("i", $userId);
   $statement -> execute();
   $result = $statement -> get_result();
   $content = $result->fetch_all(MYSQLI_ASSOC);
   if($content){ //if an active trip was found
      //get more info about the driver
      $selQuery = "SELECT * FROM providers WHERE id = ? ";
      $statement = $db->prepare($selQuery);
      $statement -> bind_param("i", $content[0]["driverId"]);
      $statement -> execute();
      $result = $statement -> get_result();
      $drivercontent = $result->fetch_all(MYSQLI_ASSOC);
      $content[0]["otherName"] = $drivercontent[0]["first_name_or_business_name"];
      $content[0]["otherProfile"] = $drivercontent[0]["profile_picture"];
      $content[0]["totalStars"] = $drivercontent[0]["totalStars"];
      $content[0]["ratingsNum"] = $drivercontent[0]["ratingsNum"];
      echo json_encode($content[0]);
   } else {
      echo json_encode("NONE");
   }

 }


?>