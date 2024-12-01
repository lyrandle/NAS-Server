<?php
// Start session to track messages
session_start();

$backupDir = '/var/backups';
$restoreDir = '/mnt/nas';

// Get the latest backup
$latestBackup = exec("ls -t $backupDir/*.tar.gz | head -n 1");

if ($latestBackup) {
    // Backup file found, display the found backup message
    $_SESSION['message'] = "Found backup: $latestBackup";
    $_SESSION['message_type'] = "success"; // Success message

    // Check the contents of the backup file before extracting
    $checkCommand = "tar -tzf $latestBackup";
    $contents = shell_exec($checkCommand);
    $_SESSION['message'] .= "\nContents of the backup:\n$contents";  // Append contents to the message for debugging

    // Command to extract the backup
    $command = "tar -xzvf $latestBackup --no-same-owner --no-same-permissions -C $restoreDir --strip-components=2";

    exec($command, $output, $return_var);

    if ($return_var === 0) {
        $_SESSION['message'] = "Restoration completed successfully."; // Success message
        $_SESSION['message_type'] = "success"; // Success type
    } else {
        // If the restoration fails, set an error message
        $_SESSION['message'] = "Restoration failed. Details:\n" . implode("\n", $output);
        $_SESSION['message_type'] = "error"; // Error type
    }
} else {
    // If no backup is found, set an error message
    $_SESSION['message'] = "No backup found in $backupDir.";
    $_SESSION['message_type'] = "error"; // Error type
}

// Redirect to the dashboard to display the message
header("Location: dashboard.php");
exit;
?>

