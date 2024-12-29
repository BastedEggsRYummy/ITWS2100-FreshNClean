<?php
// Start the session
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id']) && !isset($_SESSION['provider_id'])) {
  header("Location: login.php");
  exit();
}

$firstName = '';
$last_name = '';
$business_name = '';

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

if (isset($_POST['fname'])) {

  if (isset($_POST['lname'])){
    $nameUpdateQuery =  $conn->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE id = ?");
    $nameUpdateQuery->bind_param("sss", $_POST['fname'], $_POST['lname'], $_SESSION['user_id']);
    if ($nameUpdateQuery->execute()){
      $_SESSION['first_name_or_business_name'] = $_POST['fname'];
      $_SESSION['last_name'] = $_POST['lname'];
      header("Location: profile.php");
      exit();
    } else {
      $userErrorMsg = "Error: " . $nameUpdateQuery->error;
    }
  } else {
    $nameUpdateQuery =  $conn->prepare("UPDATE users SET first_name = ? WHERE id = ?");
    $nameUpdateQuery->bind_param("ss", $_POST['fname'],  $_SESSION['user_id']);
    if ($nameUpdateQuery->execute()){
      $_SESSION['first_name_or_business_name'] = $_POST['fname'];
      header("Location: profile.php");
      exit();
    } else {
      $userErrorMsg = "Error: " . $nameUpdateQuery->error;
    }
  }
    
}
if (isset($_POST['business_name'])) {

  if (isset($_POST['lname'])){
    $nameUpdateQuery =  $conn->prepare("UPDATE providers SET first_name_or_business_name = ?, last_name = ? WHERE id = ?");
    $nameUpdateQuery->bind_param("sss", $_POST['business_name'], $_POST['lname'], $_SESSION['provider_id']);
    if ($nameUpdateQuery->execute()){
      $_SESSION['first_name_or_business_name'] = $_POST['business_name'];
      $_SESSION['last_name'] = $_POST['lname'];
      header("Location: profile.php");
      exit();
    } else {
      $userErrorMsg = "Error: " . $nameUpdateQuery->error;
    }
  } else {
    $nameUpdateQuery =  $conn->prepare("UPDATE providers SET first_name_or_business_name = ? WHERE id = ?");
    $nameUpdateQuery->bind_param("ss", $_POST['business_name'],  $_SESSION['provider_id']);
    if ($nameUpdateQuery->execute()){
      $_SESSION['first_name_or_business_name'] = $_POST['business_name'];
      header("Location: profile.php");
      exit();
    } else {
      $userErrorMsg = "Error: " . $nameUpdateQuery->error;
    }
  }
}
?>
<?php
  include("../resources/header.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Change Name</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel ="stylesheet" href="/resources/headerFooter.css" >
    <link rel="stylesheet" href="/resources/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link href="./loginResources/login.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id = "content">
<div id="formContainer">
    <div class="container">
            <form id="ChangeName" action="changeName.php" method="post">
                <h1>Change Name</h1>
                <?php if (!empty($userErrorMsg)) : ?>
                    <p style="color:red;"><?php echo $userErrorMsg; ?></p>
                <?php endif; ?>

                
                <?php if (isset($_SESSION['user_id'])) : ?>
                  <div class="input-group">
                    <input type="text" name="fname" id="fname" placeholder="First Name" value="<?php echo htmlspecialchars($firstName); ?>" required>
                  </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['provider_id'])) : ?>
                  <div class="input-group">
                    <input type="text" name="business_name" id="business_name" placeholder="First Name/Business Name" value="<?php echo htmlspecialchars($business_name); ?>" required>
                  </div>
                <?php endif; ?>
                <div class="input-group">
                    <input type="text" name="lname" id="lname" placeholder="Last Name (Optional)" value="<?php echo htmlspecialchars($last_name); ?>">
                </div>
                
                <div class="input-group">
                    <button type="submit" id="name_update" name="name_update">Update Name</button>
                </div>
            </form>
    </div>
</div>
</div>
    

</body>
<?php
  include("../resources/footer.php");
?>

</html>