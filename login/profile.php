<?php
// Start the session
session_start();

if (isset($_POST['logout'])) {
  // Destroy the session and clear session data
  session_unset();
  session_destroy();

  // Redirect to login page
  header("Location: login.php");
  exit();
}

// Redirect to login if not logged in
if (!isset($_SESSION['user_id']) && !isset($_SESSION['provider_id'])) {
    header("Location: login.php");
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

// Fetch user or provider data based on session
$user = null;
$provider = null;

if (isset($_SESSION['user_id'])) {
    // Fetch user details
    $userId = $_SESSION['user_id'];
    $query = $conn->prepare("SELECT first_name, last_name, email, profile_picture FROM users WHERE id = ?");
    $query->bind_param("i", $userId);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();
}

if (isset($_SESSION['provider_id'])) {
    // Fetch provider details
    $providerId = $_SESSION['provider_id'];
    $query = $conn->prepare("SELECT first_name_or_business_name, last_name, email, profile_picture FROM providers WHERE id = ?");
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
    <title>Profile</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../resources/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link href="./loginResources/profile.css" rel="stylesheet" type="text/css">
    <link rel ="stylesheet" href="/resources/headerFooter.css" >

</head>

<body>
<?php 
    include("../resources/header.php");
?>

    <div id="profile-container">
        <?php if ($user): ?>
            <div id = "image">
            <img src="../<?php echo htmlspecialchars($user['profile_picture'])?>" alt="Profile Picture">
            </div>
            <div id = "info">
            <h1>Profile</h1>
            <div class="profile">
                <div class = "name">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['first_name']) .  " " . htmlspecialchars($user['last_name']); ?></p>
                    <form action="changeName.php" method="post">
                        <button type="submit" name="changeName">Change Name</button>
                    </form>
                </div>
                <div class = "email">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <form action="changeEmail.php" method="post">
                        <button type="submit" name="changeEmail">Change Email</button>
                    </form> 
                </div>
                <div class="pfp">
                    <p><strong>Change Profile Picture:</strong></p>
                    <form action="loginResources/uploadImage.php" method="post" enctype="multipart/form-data" class="pfpForm">
                        <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png" required><br><br>
                        <button type="submit" name="submitUser">Upload</button>
                    </form>
                </div>
                <div class = "upcoming-run">
                    <p><strong>Schedule a Run:</strong></p>
                    <form action="../Schedule/schedule.php" method="post">
                        <button type="submit" name="upcoming-run">Schedule</button>
                    </form> 
                </div>
                <div class = "last-run">
                    <p><strong>View Previous Runs:</strong></p>
                    <form action="../History/index.php" method="post">
                        <button type="submit" name="last-run">View History</button>
                    </form> 
                </div>
            </div>
        <?php endif; ?>

        <?php if ($provider): ?>
            <div id = "image">
            <img src="../<?php echo htmlspecialchars($provider['profile_picture'])?>" alt="Profile Picture">
            </div>
            <div id = "info">
            <h1>Profile</h1>
            <div class="profile">
                <div class = "name">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($provider['first_name_or_business_name']) .  " " . htmlspecialchars($provider['last_name']); ?></p>
                    <form action="changeName.php" method="post">
                        <button type="submit" name="changeName">Change Name</button>
                    </form>
                </div>
                <div class = "email">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($provider['email']); ?></p>
                    <form action="changeEmail.php" method="post">
                        <button type="submit" name="changeEmail">Change Email</button>
                    </form> 
                </div>
                <div class="pfp">
                    <p><strong>Change Profile Picture:</strong></p>
                    <form action="loginResources/uploadImage.php" method="post" enctype="multipart/form-data" class="pfpForm">
                        <input type="file" name="image" id="image" accept="image/*" required><br><br>
                        <button type="submit" name="submitProvider">Upload</button>
                    </form>
                </div>
                <div class = "upcoming-run">
                    <p><strong>Schedule a Run:</strong> </p>
                    <form action="../Schedule/schedule.php" method="post">
                        <button type="submit" name="upcoming-run">Schedule</button>
                    </form> 
                </div>
                <div class = "last-run">
                    <p><strong>View Previous Runs:</strong></p>
                    <form action="../History/index.php" method="post">
                        <button type="submit" name="last-run">View History</button>
                    </form> 
                </div>
            </div>
        <?php endif; ?>
        
        <form action="profile.php" method="post">
            <button type="submit" name="logout" id="logout">Logout</button>
        </form>
        </div>
    </div>
    <!--footer-->
    <?php
        include("../resources/footer.php");
    ?>
</body>

</html>
