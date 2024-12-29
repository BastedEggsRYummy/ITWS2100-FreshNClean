<?php
// Start the session
session_start();

if (!isset($_SESSION['user_id']) && !isset($_SESSION['provider_id'])) {
    header("Location: ../login/login.php");
    exit();
}

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

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <link rel="stylesheet" href="./index.css">
    <link rel ="stylesheet" href="/resources/headerFooter.css" >
    <link rel="stylesheet" href="/resources/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
    <?php
        include("../resources/header.php")
    ?>

    <main>
        <h1><b>Laundry History</b></h1>

        <table class="centered-table">
            <?php
                if (isset($_SESSION['user_id'])) {
                    // Fetch user details
                    $userId = $_SESSION['user_id'];
                    
                    echo "<tr>
                        <th>Date</th>
                        <th>Driver Name</th>
                        <th>Driver Email</th>
                        <th>Trip Rating</th>
                        <th>Laundromat</th>
                        <th>Cost</th>
                    </tr>";
                    $sql = "SELECT * FROM history WHERE user_id = ?";
                    $query = $conn->prepare($sql);
                    $query->bind_param("i", $userId);
                    $query->execute();
                    $result = $query->get_result();

                    // Check if there are results
                    if ($result->num_rows > 0) {

                        while ($row = $result->fetch_assoc()) {
                            $instructions = json_decode($row["instructions"], true);
                            $driverSql = "SELECT * FROM providers WHERE id = ?";
                            $driverQuery = $conn->prepare($driverSql);
                            $driverQuery->bind_param("i", $row["provider_id"]);
                            $driverQuery->execute();
                            $driverInfo = $driverQuery->get_result();
                            $driverInf = $driverInfo->fetch_assoc();
                            $driverQuery->close();
                            echo "<tr class=\"entry\" data-details=\"" . $instructions["loads"] . ", " . $instructions["washcycle"] . ", " . $instructions["washtemp"] . ", " . $instructions["drytime"] . " minutes, " . $instructions["drytemp"] . ", " . $instructions["additional"] . "\">";
                            echo "<td>" . $row['transaction_date'] . "</td>";
                            echo "<td>" . $driverInf["first_name_or_business_name"] . " " . $driverInf["last_name"] . "</td>"; 
                            echo "<td>" . $driverInf["email"] . "</td>";
                            echo "<td>" . ($row['ratingOfDriver']/2.0) . "/5</td>";
                            echo "<td>" . $row['cleaningLocation'] . "</td>";
                            echo "<td>$" . $row['price'] . "</td>";
                            echo "</tr>";
                        }
                    }

                    $query->close();
                }
                
                if (isset($_SESSION['provider_id'])) {
                    // Fetch provider details
                    $providerId = $_SESSION['provider_id'];
                    echo "<tr>
                        <th>Date</th>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>Trip Rating</th>
                        <th>Laundromat</th>
                        <th>Payment</th>
                    </tr>";

                    $sql = "SELECT * FROM history WHERE provider_id = ?";
                    $query = $conn->prepare($sql);
                    $query->bind_param("i", $providerId);
                    $query->execute();
                    $result = $query->get_result();
                    $query->close();
                    // Check if there are results
                    if ($result->num_rows > 0) {

                        while ($row = $result->fetch_assoc()) {
                            $customerSql = "SELECT * FROM users WHERE id = ?";
                            $customerQuery = $conn->prepare($customerSql);
                            $customerQuery->bind_param("i", $row["user_id"]);
                            $customerQuery->execute();
                            $customerInfo = $customerQuery->get_result();
                            $custInf = $customerInfo->fetch_assoc();
                            $customerQuery->close();
                            $instructions = json_decode($row["instructions"], true);
                            echo "<tr class=\"entry\" data-details=\"" . $instructions["loads"] . ", " . $instructions["washcycle"] . ", " . $instructions["washtemp"] . ", " . $instructions["drytime"] . " minutes, " . $instructions["drytemp"] . ", " . $instructions["additional"] . "\">";
                            echo "<td>" . $row['transaction_date'] . "</td>";
                            echo "<td>" . $custInf["first_name"] . " " . $custInf["last_name"] . "</td>"; 
                            echo "<td>" . $custInf["email"] . "</td>";
                            echo "<td>" . ($row['ratingOfUser']/2.0) . "/5</td>";
                            echo "<td>" . $row['cleaningLocation'] . "</td>";
                            echo "<td>$" . $row['price'] . "</td>";
                            echo "</tr>";
                        } 
                    } 
                }
            ?>
            </table>
        <div id="popup" class="popup">
            <div class="popup-content">
                <span class="close">&times;</span>
                <h3>Laundry Instructions</h3><br>
                <div id="popup-info"> <!-- Container for dynamic content --></div>
            </div>
        </div>        
    </main>
    <script src="index.js">    
    </script>      
</body>
<?php
        include("../resources/footer.php")
?>
</html>