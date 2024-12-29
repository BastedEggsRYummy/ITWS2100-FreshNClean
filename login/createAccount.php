<?php
// Start the session
session_start();

if (isset($_SESSION['user_id']) || isset($_SESSION['provider_id'])) {
    header("Location: profile.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Variables to hold error messages and input data for both forms
$userErrorMsg = '';
$providerErrorMsg = '';

$firstName = '';
$last_name = '';
$email = '';
$business_name = '';
$provider_email = '';
$service_type = '';

// Database connection details
$servername = "localhost";
$username = "phpmyadmin";
$passwordDB = "my@dm1n";
$dbname = "Users"; 

// Create connection
$conn = new mysqli($servername, $username, $passwordDB, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle user registration form submission
if (isset($_POST['save_user'])) {
    session_unset();
    // Get form data and sanitize inputs
    $firstName = trim($_POST['firstName']);
    $last_name = trim($_POST['last_name']);
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    // Validate input
    if (empty($firstName) || empty($last_name) || empty($email) || empty($password) || empty($confirm)) {
        $userErrorMsg = 'All fields are required.';
    } elseif ($password !== $confirm) {
        $userErrorMsg = 'Passwords do not match.';
    } elseif (strlen($password) < 8) {
        $userErrorMsg = 'Password must be at least 8 characters long.';
    } else {
        // Check if the email is already registered
        $emailCheckQuery = $conn->prepare("SELECT * FROM users WHERE LOWER(email) = LOWER(?)");
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

                // Hash the password
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                // Insert user data into the database
                $query = $conn->prepare("INSERT INTO users (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)");
                $query->bind_param("ssss", $firstName, $last_name, $email, $passwordHash);

                if ($query->execute()) {
                    // Store session variables
                    $_SESSION['user_id'] = $query->insert_id;
                    $_SESSION['email'] = $email;
                    $_SESSION['first_name'] = $firstName;
                    $_SESSION['last_name'] = $last_name;
                    $_SESSION['provider'] = false;

                    // Redirect to the home page
                    header("Location: profile.php");
                    exit();

                } else {
                    $userErrorMsg = "Error: " . $query->error;
                }
            }
        }
    }
}

// Handle provider registration form submission
if (isset($_POST['save_provider'])) {
    session_unset();
    // Get form data and sanitize inputs
    $business_name = trim($_POST['business_name']);
    $last_name = trim($_POST['last_name']);
    $provider_email = strtolower(trim($_POST['provider_email']));
    $provider_password = trim($_POST['provider_password']);
    $provider_confirm = trim($_POST['provider_confirm']);
    $service_type = $_POST['service_type'];

    // Validate input
    if (empty($business_name) || empty($provider_email) || empty($provider_password) || empty($provider_confirm)) {
        $providerErrorMsg = 'All required fields must be filled.';
    } elseif ($provider_password !== $provider_confirm) {
        $providerErrorMsg = 'Passwords do not match.';
    } elseif (strlen($provider_password) < 8) {
        $providerErrorMsg = 'Password must be at least 8 characters long.';
    } else {
        // Check if the email is already registered
        $emailCheckQuery = $conn->prepare("SELECT * FROM providers WHERE LOWER(email) = LOWER(?)");
        $emailCheckQuery->bind_param("s", $provider_email);
        $emailCheckQuery->execute();
        $result = $emailCheckQuery->get_result();

        if ($result->num_rows > 0) {
            $providerErrorMsg = 'Email is already registered!';
        } else {
            $emailCheckQuery = $conn->prepare("SELECT * FROM users WHERE LOWER(email) = LOWER(?)");
            $emailCheckQuery->bind_param("s", $email);
            $emailCheckQuery->execute();
            $result = $emailCheckQuery->get_result();
            if ($result->num_rows > 0) {
                $userErrorMsg = 'Email is already registered!';
            } else {
                // Hash the password
                $passwordHash = password_hash($provider_password, PASSWORD_DEFAULT);

                // Insert provider data into the database
                $query = $conn->prepare("INSERT INTO providers (first_name_or_business_name, last_name, service_type, email, password_hash) VALUES (?, ?, ?, ?, ?)");
                $query->bind_param("sssss", $business_name, $last_name, $service_type, $provider_email, $passwordHash);

                if ($query->execute()) {
                    // Store session variables
                    $_SESSION['provider_id'] = $query->insert_id;
                    $_SESSION['email'] = $provider_email;
                    $_SESSION['first_name_or_business_name'] = $business_name;
                    $_SESSION['last_name'] = $last_name;
                    $_SESSION['service_type'] = $service_type;
                    $_SESSION['provider'] = true;

                    // Redirect to provider dashboard
                    header("Location: profile.php");
                    exit();
                } else {
                    $providerErrorMsg = "Error: " . $query->error;
                }
            }
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create Account</title>
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
<div id="content">
    <div id="formContainer">
    <div class="container">
        <!-- User Account Creation Form -->
        <form id="UserSignup" action="createAccount.php" method="post">
            <h1>Create User Account</h1>

            <!-- Display any error messages for users -->
            <?php if (!empty($userErrorMsg)) : ?>
                <p style="color:red;"><?php echo $userErrorMsg; ?></p>
            <?php endif; ?>

            <div class="input-group">
                <input type="email" name="email" id="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Password" required>
            </div>

            <div class="input-group">
                <input type="password" name="confirm" id="confirm" placeholder="Re-enter Password" required>
            </div>

            <div class="input-group">
                <input type="text" name="firstName" id="firstName" placeholder="First Name" value="<?php echo htmlspecialchars($firstName); ?>" required>
            </div>

            <div class="input-group">
                <input type="text" name="last_name" id="last_name" placeholder="Last Name" value="<?php echo htmlspecialchars($last_name); ?>" required>
            </div>

            <div class="input-group">
                <button type="submit" id="save_user" name="save_user">Create User Account</button>
            </div>
        </form>
    </div>
    
      <div class="container">
          <!-- Provider Account Creation Form -->
          <form id="ProviderSignup" action="createAccount.php" method="post">
              <h1>Create Provider Account</h1>

              <!-- Display any error messages for providers -->
              <?php if (!empty($providerErrorMsg)) : ?>
                  <p style="color:red;"><?php echo $providerErrorMsg; ?></p>
              <?php endif; ?>

              <div class="input-group">
                  <input type="email" name="provider_email" id="provider_email" placeholder="Email" value="<?php echo htmlspecialchars($provider_email); ?>" required>
              </div>

              <div class="input-group">
                  <input type="password" name="provider_password" id="provider_password" placeholder="Password" required>
              </div>

              <div class="input-group">
                  <input type="password" name="provider_confirm" id="provider_confirm" placeholder="Re-enter Password" required>
              </div>

              <div class="input-group">
                  <input type="text" name="business_name" id="business_name" placeholder="Business or First Name" value="<?php echo htmlspecialchars($business_name); ?>" required>
              </div>

              <div class="input-group">
                  <input type="text" name="last_name" id="last_name" placeholder="Last Name (Optional)" value="<?php echo htmlspecialchars($last_name); ?>">
              </div>

              <div class="input-group">
                  <label for="service_type">Service Type:</label>
                  <select name="service_type" id="service_type" required>
                      <option value="personal laundry">Personal Laundry</option>
                      <option value="laundromat">Laundromat</option>
                      <option value="both">Both</option>
                  </select>
              </div>

              <div class="input-group">
                  <button type="submit" id="save_provider" name="save_provider">Create Provider Account</button>
              </div>
          </form>
      </div>
    </div>
</div>
<p id="formSwap" style="text-align:center; margin-top:10px; margin-bottom: 40px;">Already have an account?<br><a href="/login/login.php">Login</a>
<br> By creating an account, you are agreeing to our <a href="/Documentation/Legal/index.php">Terms of Service</a> and <a href="/Documentation/Legal/privacyPolicy.php">Privacy Policy</a>.
</p>
    <?php
        include("../resources/footer.php");
    ?>
</body>

</html>
