<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Define the path to the NAS directory
$target_dir = "/mnt/nas/";
$uploadOk = 1; // Flag to determine if the upload should proceed

$_SESSION['message'] = "";
$_SESSION['message_type'] = ""; 

// Loop through all the uploaded files (for multiple files)
foreach ($_FILES['fileToUpload']['name'] as $key => $fileName) {
    $target_file = $target_dir . basename($fileName);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file was uploaded without errors
    if ($_FILES["fileToUpload"]["error"][$key] == 0) {
        if (file_exists($target_file)) {
            $_SESSION['message'] = "Sorry, the file $fileName already exists. Skipping upload.";
            $_SESSION['message_type'] = "info";
            continue;  // Skip to the next file if it exists
        }

        // Attempt to upload the file if everything is ok
        if ($uploadOk == 0) {
            if ($_SESSION['message'] == "") {
                $_SESSION['message'] = "Sorry, the file $fileName was not uploaded.";
                $_SESSION['message_type'] = "error";
            }
        } else {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$key], $target_file)) {
                $_SESSION['message'] = "File $fileName uploaded successfully.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Sorry, there was an error uploading your file $fileName.";
                $_SESSION['message_type'] = "error";
            }
        }
    } else {
        $_SESSION['message'] = "Error: " . $_FILES["fileToUpload"]["error"][$key] . " for file $fileName.";
        $_SESSION['message_type'] = "error";
    }
}

header("Location: dashboard.php");
exit;
?>
