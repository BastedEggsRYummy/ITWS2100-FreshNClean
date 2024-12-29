<?php
// Start the session
session_start();

if (isset($_SESSION['user_id']) || isset($_SESSION['provider_id'])) {
    header("Location: profile.php");
    exit();
}


// Variable to hold error messages
$errorMsgUser = '';
$errorMsgProvider = '';
$emailUser = '';
$emailProvider = '';

if (isset($_POST['user_login'])) {
    session_unset();
    // Get form data for user login
    $emailUser = strtolower(trim($_POST['email_user']));
    $passwordUser = trim($_POST['password_user']);

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

    // Validate input
    if (empty($emailUser) || empty($passwordUser)) {
        $errorMsgUser = 'All fields are required.';
    } else {
        // Check if the email is registered
        $emailCheckQuery = $conn->prepare("SELECT * FROM users WHERE LOWER(email) = LOWER(?)");
        $emailCheckQuery->bind_param("s", $emailUser);
        $emailCheckQuery->execute();
        $result = $emailCheckQuery->get_result();

        if ($result->num_rows > 0) {
            // Fetch the user data
            $user = $result->fetch_assoc();
            $hashedPassword = $user['password_hash'];

            // Verify the password
            if (password_verify($passwordUser, $hashedPassword)) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['provider'] = false;

                // Redirect to index page
                header("Location: profile.php");
                exit();
            } else {
                $errorMsgUser = 'Incorrect email/password.';
            }
        } else {
            $errorMsgUser = 'Incorrect email/password.';
        }
    }

    $conn->close();
}

if (isset($_POST['provider_login'])) {
    session_unset();
    // Get form data for provider login
    $emailProvider = strtolower(trim($_POST['email_provider']));
    $passwordProvider = trim($_POST['password_provider']);

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

    // Validate input
    if (empty($emailProvider) || empty($passwordProvider)) {
        $errorMsgProvider = 'All fields are required.';
    } else {
        // Check if the email is registered
        $emailCheckQuery = $conn->prepare("SELECT * FROM providers WHERE LOWER(email) = LOWER(?)");
        $emailCheckQuery->bind_param("s", $emailProvider);
        $emailCheckQuery->execute();
        $result = $emailCheckQuery->get_result();

        if ($result->num_rows > 0) {
            // Fetch the provider data
            $provider = $result->fetch_assoc();
            $hashedPassword = $provider['password_hash'];

            // Verify the password
            if (password_verify($passwordProvider, $hashedPassword)) {
                // Set session variables
                $_SESSION['provider_id'] = $provider['id'];
                $_SESSION['email'] = $provider['email'];
                $_SESSION['first_name'] = $provider['business_name'];
                $_SESSION['provider'] = true;

                // Redirect to provider dashboard
                header("Location: profile.php");
                exit();
            } else {
                $errorMsgProvider = 'Incorrect email/password.';
            }
        } else {
            $errorMsgProvider = 'Incorrect email/password.';
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
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
            <!-- User Login Form -->
            <form id="LoginUser" action="login.php" method="post">
                <h1>User Login</h1>

                <!-- Display error message for user login -->
                <?php if (!empty($errorMsgUser)) : ?>
                    <p style="color:red;"><?php echo $errorMsgUser; ?></p>
                <?php endif; ?>

                <div class="input-group">
                    <input type="email" name="email_user" id="email_user" placeholder="Email" value="<?php echo htmlspecialchars($emailUser); ?>" required>
                </div>

                <div class="input-group">
                    <input type="password" name="password_user" id="password_user" placeholder="Password" required>
                </div>

                <div class="input-group">
                    <button type="submit" id="user_login" name="user_login">Login</button>
                </div>
            </form>
            </div>
            <div class="container">
            <!-- Provider Login Form -->
            <form id="LoginProvider" action="login.php" method="post">
                <h1>Provider Login</h1>

                <!-- Display error message for provider login -->
                <?php if (!empty($errorMsgProvider)) : ?>
                    <p style="color:red;"><?php echo $errorMsgProvider; ?></p>
                <?php endif; ?>

                <div class="input-group">
                    <input type="email" name="email_provider" id="email_provider" placeholder="Email" value="<?php echo htmlspecialchars($emailProvider); ?>" required>
                </div>

                <div class="input-group">
                    <input type="password" name="password_provider" id="password_provider" placeholder="Password" required>
                </div>

                <div class="input-group">
                    <button type="submit" id="provider_login" name="provider_login">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<p id="formSwap" style="text-align:center; margin-top:-40px; margin-bottom: 40px;">New to  Fresh n' Clean?<br><a href="/login/createAccount.php">Create a new account</a></p>    

<?php
    include("../resources/footer.php");
?>
</body>

</html>
