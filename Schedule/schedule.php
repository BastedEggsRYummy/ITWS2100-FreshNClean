
<?php
// Start the session
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Database connection details
$servername = "localhost";
$username = getenv('PHPUSER');
$passwordDB = getenv('PHPPWD');
$dbname = "Users";

// Create connection
$conn = new mysqli($servername, $username, $passwordDB, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user or provider data based on session
$user = null;
$provider = null;

if (isset($_SESSION['user_id'])) {
    // Fetch user details
    $userId = $_SESSION['user_id'];
    $query = $conn->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
    $query->bind_param("i", $userId);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();
}

if (isset($_SESSION['provider_id'])) {
    // Fetch provider details
    $providerId = $_SESSION['provider_id'];
    $query = $conn->prepare("SELECT first_name_or_business_name, last_name, email FROM providers WHERE id = ?");
    $query->bind_param("i", $providerId);
    $query->execute();
    $result = $query->get_result();
    $provider = $result->fetch_assoc();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <title>Fresh n Clean - Visionary Vortex</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


    <link rel="stylesheet" href="schedule.css">
    <!-- load in fontawesome for icons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- access google places api-->

   <?php include("scheduleScripts.php") ?>
   <script async
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo getenv("MAPSKEY") ?>&loading=async&libraries=places&callback=init">
</script>
   <link rel ="stylesheet" href="/resources/headerFooter.css" >
   <link rel="stylesheet" href="/resources/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
<?php include("../resources/header.php") ?>

   <main>
      <h1 id="status">Schedule Laundry Pick Up</h1>
      <div id="mapcontent">
         <figure id="map">
            <!-- <gmp-map id="map" center="42.72956085205078,-73.673828125" zoom="14" map-id="DEMO_MAP_ID"> -->
         </figure>
         <div id="laundromats">
            <!-- <div>
               <h2>item 1</h2>
               <p>description</p>
            </div>
            <div>
               <h2>item 2</h2>
               <p>description</p>
            </div>
            <div>
               <h2>item 3</h2>
               <p>description</p>
            </div>
            <div>
               <h2>item 4</h2>
               <p>description</p>
            </div> -->
         </div>
      </div>
      <div id="schedulepopup">
         <label>Pick a date and time for your laundry pickup (Currently Not Implemented) </label>
         <input type="date" id="scheduledate" required>
         <input type="time" id="scheduletime" required>
         <button id="schedulesubmit">Submit</button>
         <p class="delbutton">X</p>
      </div>
      <!-- <h3 id="status"></h3> -->
      <div id="driverdiv">

      </div>
      <div class="buttondiv">
         <div class="driverbuttons">
            <button id="startshift" class="btn btn-dark">Toggle Active</button>
         </div>
         <div class="customerbuttons">
            <button id="getlaundry" class = "btn btn-dark">Get laundry now</button>
            <button id="schedulelaundry" class="btn btn-dark">Schedule laundry</button>
         </div>

      </div>
      <div class="ratePopup">

      </div>
   </main>
   <!-- instructions element -->
   <form id="instructions">
    <h2>Laundry Instructions</h2>
    <div id="load">
        <label for="loadNumber"># of Loads</label>
        <input type="number" id="loadNumber" name="loadNumber" min="1" max="10">
    </div>
    <div id="washerAndDryer">
        <div id="wash">
            <div>
                <label for="washCycle">Wash Cycle</label>
                <select name="washCycle" id="washCycle" required>
                    <option value="">--</option>
                    <option value="Quick Wash">Quick Wash</option>
                    <option value="Delicate">Delicate</option>
                    <option value="Normal">Normal</option>
                    <option value="Heavy Duty">Heavy Duty</option>
                    <option value="Bulky Items">Bulky Items</option>
                </select>
            </div>
            <div>
                <label for="washTemperature">Wash Temperature</label>
                <select name="washTemperature" id="washTemperature" required>
                    <option value="">--</option>
                    <option value="Cold">Cold</option>
                    <option value="Cool">Cool</option>
                    <option value="Warm">Warm</option>
                    <option value="Hot">Hot</option>
                </select>
            </div>
        </div>
        <div id="dry">
            <div>
                <label for="dryTime">Dry Time</label>
                <input type="number" id="dryTime" name="dryTime" min="30" max="240" step="30">
                minutes
            </div>
            <div>
                <label for="dryTemperature">Dry Temperature</label>
                <select name="dryTemperature" id="dryTemperature" required>
                    <option value="">--</option>
                    <option value="Cold">Cold</option>
                    <option value="Cool">Cool</option>
                    <option value="Warm">Warm</option>
                    <option value="Hot">Hot</option>
                </select>
            </div>
        </div>
    </div>
    <div>
        <label for="additional">Additional Instructions</label><br>
        <textarea id="additional" name="additional"></textarea>
    </div>
    <button id="instructionssubmit">Submit</button>
   </form>

   <?php include("../resources/footer.php") ?>
</body>