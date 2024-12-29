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
   session_start();
   //read in trip info
   $userId = $_POST["userId"];
   $driverId = $_POST["driverId"];
   $transactionId = $_POST["transactionId"];
   $rating = $_POST["rating"];
   if (isset($_SESSION['provider_id'])) {
      //driver is logged in; update user info
      $selQuery = "UPDATE users SET ratingsNum = ratingsNum + 1, totalStars = totalStars + ? WHERE id = ?";
      $statement = $db->prepare($selQuery);
      $statement -> bind_param("ii", $rating,$userId);   
      $statement -> execute();
      //update ratingOfUser in history
      $selQuery = "UPDATE history SET ratingOfUser = ?  WHERE transaction_id = ?";
      $statement = $db->prepare($selQuery);
      $statement -> bind_param("ii", $rating,$transactionId);   
      $statement -> execute();
      echo ("SUCCESS");
   } else {
      //user is logged in; update driver info
      $selQuery = "UPDATE providers SET ratingsNum = ratingsNum + 1, totalStars = totalStars + ? WHERE id = ?";
      $statement = $db->prepare($selQuery);
      $statement -> bind_param("ii", $rating,$driverId);   
      $statement -> execute();
      //update ratingOfUser in history
      $selQuery = "UPDATE history SET ratingOfDriver = ?  WHERE transaction_id = ?";
      $statement = $db->prepare($selQuery);
      $statement -> bind_param("ii", $rating,$transactionId);   
      $statement -> execute();
      echo ("SUCCESS");
   }


 }


?>