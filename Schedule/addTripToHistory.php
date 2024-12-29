<?php
@ $db = new mysqli('localhost',  getenv('PHPUSER'),  getenv('PHPPWD'), 'Users');
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
   $content;
   //read driver id and user id from input
   if (isset($_SESSION['provider_id'])) {//get trip from driver info
      $driverId = $_SESSION['provider_id'];
      $selQuery = "SELECT * FROM activetrips WHERE driverid = ?";
      $statement = $db->prepare($selQuery);
      $statement -> bind_param("i", $driverId);
   
      $statement -> execute();
   
      $result = $statement -> get_result();
   
      $content = $result->fetch_all(MYSQLI_ASSOC);
   } else if (isset($_SESSION['user_id'])){//get trip from user info
      $userId = $_SESSION['user_id'];
      $selQuery = "SELECT * FROM activetrips WHERE userid = ?";
      $statement = $db->prepare($selQuery);
      $statement -> bind_param("i", $userId);
   
      $statement -> execute();
   
      $result = $statement -> get_result();
   
      $content = $result->fetch_all(MYSQLI_ASSOC);
   } else {
      //huh?
      echo "no logged in user found";
   }
   
   $driverId = $content[0]["driverId"];
   //get driver content
   $selQuery = "SELECT * FROM providers WHERE id = ?"; //WHERE lat ...
   $statement = $db->prepare($selQuery);
   $statement -> bind_param("i", $driverId);
   $statement -> execute();
   $result = $statement -> get_result();
   $drivercontent = $result->fetch_all(MYSQLI_ASSOC);

   //add trip to history
   $trip = $content[0];
   //read info from active trip
   $driverId = $trip["driverId"];
   $userId = $trip["userId"];
   $date = date("Y-m-d");
   $price = 10.50; //do we need to make an algorithm for this?
   //$serviceType = "Laundromat"; //read service type from driver id
   $instructions = $trip["instructions"];
   $result = $_POST["status"];
   $driverEmail = $drivercontent[0]["email"];
   $driverName = $trip["driverName"];
   $cleaningLocation = $trip["laundromatName"];
   //cleaning location
   $insQuery = "INSERT INTO history (`user_id`, `provider_id`, `transaction_date`, `price`, `instructions`, `result`, `driverEmail`, `driverName`, `cleaningLocation`) values(?,?,?,?,?,?,?,?,?)";
   $statement = $db->prepare($insQuery);
   $statement -> bind_param( "iisdsssss", $userId, $driverId, $date, $price, $instructions, $result, $driverEmail, $driverName, $cleaningLocation);
   $statement->execute();

   //delete trip from activetrips, can use driver or userid to find trip
   $selQuery = "DELETE FROM activetrips WHERE userid = ?";
   $statement = $db->prepare($selQuery);
   $statement -> bind_param("i", $userId);
   $statement -> execute();

   //clear driver_location
   $selQuery = "DELETE FROM driver_location WHERE id = ?"; //WHERE lat ...
   $statement = $db->prepare($selQuery);
   $statement -> bind_param("i", $driverId);
   $statement -> execute();

   //done
   echo "SUCCESS";

}
?>