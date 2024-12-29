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
   //read in id from session
   session_start();
   $driverId = $_SESSION['provider_id'];

   $selQuery = "SELECT * FROM activetrips WHERE driverid = ?";
   $statement = $db->prepare($selQuery);
   $statement -> bind_param("i", $driverId);
   $statement -> execute();
   $result = $statement -> get_result();
   $content = $result->fetch_all(MYSQLI_ASSOC);
   if($content){ //if an active trip was found
    //get more info about the user to display
    $selQuery = "SELECT * FROM users WHERE id = ? ";
    $statement = $db->prepare($selQuery);
    $statement -> bind_param("i", $content[0]["userId"]);
    $statement -> execute();
    $result = $statement -> get_result();
    $usercontent = $result->fetch_all(MYSQLI_ASSOC);
    $content[0]["otherName"] = $usercontent[0]["first_name"];
    $content[0]["otherProfile"] = $usercontent[0]["profile_picture"];
    $content[0]["totalStars"] = $usercontent[0]["totalStars"];
    $content[0]["ratingsNum"] = $usercontent[0]["ratingsNum"];
    echo json_encode($content[0]);
   } else {
      echo json_encode("NONE");
   }

 }


?>