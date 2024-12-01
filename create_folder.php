<?php
// Start the session to track user login state
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Clear any previous messages
$_SESSION['message'] = "";
$_SESSION['message_type'] = ""; // success or error

// Handle folder creation when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the folder name from the form
    $folderName = trim($_POST['folderName']);
    
    // Define the path to the NAS directory
    $target_dir = "/mnt/nas/";

    // Combine the target directory path with the folder name to create the folder path
    $target_folder = $target_dir . $folderName;

    // Check if the folder already exists
    if (is_dir($target_folder)) {
        $_SESSION['message'] = "Sorry, the folder already exists.";
        $_SESSION['message_type'] = "error";
    } else {
        // Attempt to create the folder
        if (mkdir($target_folder, 0777, true)) {
            $_SESSION['message'] = "Folder created successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Sorry, there was an error creating the folder.";
            $_SESSION['message_type'] = "error";
        }
    }
    
    // Redirect back to the dashboard page to show the message pop-up
    header("Location: dashboard.php");
    exit;
}
?>

