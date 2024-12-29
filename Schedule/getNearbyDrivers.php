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
   //read in user location in lat and lon
   $lat = $_POST['lat'];

   $latmin = $lat - 10;

   $lon = $_POST['lon'];

   $ten = 10;
   $one = 1;
   $free = "free";
   //get drivers where difference in lat and lon is less than .001
   // $cityName = $_POST["cityName"];
   // //get city from DB
   $selQuery = "SELECT * FROM driver_location WHERE latitude > ? AND status = ?"; //WHERE lat ...
   $statement = $db->prepare($selQuery);
   $statement -> bind_param("is", $latmin, $free);

   $statement -> execute();


   $result = $statement -> get_result();

   $content = $result->fetch_all(MYSQLI_ASSOC);

   #print count($content);
   if($content){
      //item exists in database; return info as json
      //select closest driver
      $lowestid = 1;
      $lowestdelta = 100;
      for($i = 0; $i < count($content);$i++){ //temp
        //key $y value $x
        $currdelta = abs($content[$i]["longitude"] - $lon) + abs($content[$i]["latitude"] - $lat); 
        if($currdelta < $lowestdelta){
          $lowestdelta = $currdelta;
          $lowestid = $content[$i]["id"];
        }
      }
      //fetch closest driver
      $selQuery = "SELECT * FROM providers WHERE id = ?"; //WHERE lat ...
      $statement = $db->prepare($selQuery);
      $statement -> bind_param("i", $lowestid);
   
      $statement -> execute();
   
   
      $result = $statement -> get_result();
   
      $content2 = $result->fetch_all(MYSQLI_ASSOC);


      //get loc vars of the closest driver
      $selQuery = "SELECT * FROM driver_location WHERE id = ?"; //WHERE lat ...
      $statement = $db->prepare($selQuery);
      $statement -> bind_param("i", $lowestid);
   
      $statement -> execute();
   
   
      $result = $statement -> get_result();
   
      $content = $result->fetch_all(MYSQLI_ASSOC);

      //add location info to driver construct
      $content2[0]["lng"] = $content[0]["longitude"];
      $content2[0]["lat"] = $content[0]["latitude"];
      //get driver info from drivers db
      echo json_encode($content2);
     } else {
      //item doesnt exist
      echo json_encode("NOITEM");
     }




  exit;
 }
?>