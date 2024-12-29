<?php
session_start();

$servername = "localhost";
$username = getenv('PHPUSER');
$passwordDB = getenv('PHPPWD');
$dbname = "Users"; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create connection
$conn = new mysqli($servername, $username, $passwordDB, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submitUser'])) {
    // Directory where the uploaded file will be stored
    $targetDir = "../../uploads/";
    $id = $_SESSION['user_id'];

    // Generate a consistent filename using email
    $fileExtension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION); 
    $uniqueName = "user" . $id; 
    $fileName = $uniqueName . "." . $fileExtension;
    $fileName = strtolower($fileName);

    $targetFilePath = $targetDir . $fileName;
    $dbPath = "uploads/" . $fileName;

    // Check file type (only allow images)
    $fileType = strtolower($fileExtension);
    $allowedTypes = ["jpg", "jpeg", "png"];

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            echo "The file has been uploaded successfully as: " . htmlspecialchars($fileName);
            $pictureUpdateQuery =  $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
            $pictureUpdateQuery->bind_param("ss", $dbPath, $_SESSION['user_id']);
            if ($pictureUpdateQuery->execute()){
                header("Location: ../profile.php");
            exit();
            } else {
            echo "Error: " . $pictureUpdateQuery->error;
            }
            header("Location: ../profile.php");
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Invalid file type. Only JPG, JPEG, and PNG files are allowed.";
    }
}
if (isset($_POST['submitProvider'])) {
    // Directory where the uploaded file will be stored
    $targetDir = "../../uploads/";
    $id = $_SESSION['provider_id'];

    // Generate a consistent filename using email
    $fileExtension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION); 
    $uniqueName = "provider" . $id; 
    $fileName = $uniqueName . "." . $fileExtension;

    $targetFilePath = $targetDir . $fileName;
    $dbPath = "uploads/" . $fileName;
    // Check file type (only allow images)
    $fileType = strtolower($fileExtension);
    $allowedTypes = ["jpg", "jpeg", "png"];

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            echo "The file has been uploaded successfully as: " . htmlspecialchars($fileName);
            $pictureUpdateQuery =  $conn->prepare("UPDATE providers SET profile_picture = ? WHERE id = ?");
            $pictureUpdateQuery->bind_param("ss", $dbPath, $_SESSION['provider_id']);
            if ($pictureUpdateQuery->execute()){
                header("Location: ../profile.php");
            exit();
            } else {
            echo "Error: " . $pictureUpdateQuery->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Invalid file type. Only JPG, JPEG, and PNG files are allowed.";
    }
    
}


