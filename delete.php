<?php
session_start();

// Check if the user is an admin
if ($_SESSION['permissions'] !== 'admin') {
    $_SESSION['message'] = "You do not have permission to delete.";
    $_SESSION['message_type'] = "error";
    header("Location: dashboard.php");
    exit();
}



// Function to recursively delete a folder and its contents
function deleteFolder($folderPath) {
    // Get all files and subdirectories in the folder, excluding '.' and '..'
    $files = array_diff(scandir($folderPath), array('.', '..'));

    // Loop through each file or folder
    foreach ($files as $file) {
        $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
        
        if (is_dir($filePath)) {
            // If it's a directory, recursively delete its contents
            deleteFolder($filePath);
        } else {
            // If it's a file, delete it
            unlink($filePath);
        }
    }
    
    // After deleting all contents, remove the now-empty directory
    rmdir($folderPath);
}

if (isset($_GET['file'])) {
    $filePath = '/mnt/nas/' . basename($_GET['file']); // Full file/folder path

    if (file_exists($filePath)) {
        if (is_dir($filePath)) {
            // If it's a folder, delete it and its contents (even if empty)
            deleteFolder($filePath);
            $_SESSION['message'] = "Folder and its contents deleted successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            // If it's a file, delete it
            if (unlink($filePath)) {
                $_SESSION['message'] = "File deleted successfully.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Failed to delete the file.";
                $_SESSION['message_type'] = "error";
            }
        }
    } else {
        $_SESSION['message'] = "File/Folder not found.";
        $_SESSION['message_type'] = "error";
    }

    // Redirect back to the dashboard
    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION['message'] = "No file or folder specified for deletion.";
    $_SESSION['message_type'] = "error";
    header("Location: dashboard.php");
    exit();
}
?>

