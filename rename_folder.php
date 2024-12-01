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

// Handle folder/file renaming when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the old and new folder/file names from the form
    $oldName = trim($_POST['oldName']);
    $newName = trim($_POST['newName']);
    
    // Define the path to the NAS directory
    $target_dir = "/mnt/nas/";

    // Combine the target directory path with the old and new names to get the full paths
    $old_path = $target_dir . $oldName;
    $new_path = $target_dir . $newName;

    // Check if the old folder/file exists
    if (!file_exists($old_path)) {
        $_SESSION['message'] = "Sorry, the folder/file does not exist.";
        $_SESSION['message_type'] = "error";
    } elseif (file_exists($new_path)) {
        // Check if the new folder/file name already exists
        $_SESSION['message'] = "Sorry, the new name already exists.";
        $_SESSION['message_type'] = "error";
    } else {
        // Attempt to rename the folder/file
        if (rename($old_path, $new_path)) {
            $_SESSION['message'] = "Folder/File renamed successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Sorry, there was an error renaming the folder/file.";
            $_SESSION['message_type'] = "error";
        }
    }

    // Redirect back to the dashboard page to show the message pop-up
    header("Location: dashboard.php");
    exit;
}
?>
