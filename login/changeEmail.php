<?php
// Start the session
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id']) && !isset($_SESSION['provider_id'])) {
  header("Location: login.php");
  exit();
}
$emailUser = '';
$userErrorMsg = '';

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

if (isset($_POST['email_update'])) {
  $email = strtolower(trim($_POST['email_user']));
  $emailCheckQuery = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $emailCheckQuery->bind_param("s", $email);
  $emailCheckQuery->execute();
  $result = $emailCheckQuery->get_result();

  if ($result->num_rows > 0) {
      $userErrorMsg = 'Email is already registered!';
  } else {
      $emailCheckQuery = $conn->prepare("SELECT * FROM providers WHERE LOWER(email) = LOWER(?)");
      $emailCheckQuery->bind_param("s", $email);
      $emailCheckQuery->execute();
      $result = $emailCheckQuery->get_result();
      if ($result->num_rows > 0) {
          $userErrorMsg = 'Email is already registered!';
    } else {
        if (isset($_SESSION['user_id'])){
          $emailUpdateQuery =  $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
          $emailUpdateQuery->bind_param("ss", $email, $_SESSION['user_id']);
          if ($emailUpdateQuery->execute()){
            $_SESSION['email'] = $email;
            header("Location: profile.php");
            exit();
          } else {
            $userErrorMsg = "Error: " . $emailUpdateQuery->error;
          }
          
        } else if (isset($_SESSION['provider_id'])){
          
          $emailUpdateQuery =  $conn->prepare("UPDATE providers SET email = ? WHERE id = ?");
          $emailUpdateQuery->bind_param("ss", $email,  $_SESSION['provider_id']);
          if ($emailUpdateQuery->execute()){
            $_SESSION['email'] = $email;
            header("Location: profile.php");
            exit();
          } else {
            $userErrorMsg = "Error: " . $emailUpdateQuery->error;
          }
        }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Change Email</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel ="stylesheet" href="/resources/headerFooter.css" >
    <link rel="stylesheet" href="/resources/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link href="./loginResources/login.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
  include("../resources/header.php");
?>
<div id = "content">
<div id="formContainer">
    <div class="container">
            <form id="ChangeEmail" action="changeEmail.php" method="post">
                <h1>Change Email</h1>
                <?php if (!empty($userErrorMsg)) : ?>
                    <p style="color:red;"><?php echo $userErrorMsg; ?></p>
                <?php endif; ?>

                <div class="input-group">
                    <input type="email" name="email_user" id="email_user" placeholder="Email" value="<?php echo htmlspecialchars($emailUser); ?>" required>
                </div>
                <div class="input-group">
                    <button type="submit" id="email_update" name="email_update">Update Email</button>
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