<?php

// Start session to store success/error message
session_start();

include 'db_connect.php'; 

date_default_timezone_set('America/Chicago');

$backupDir = '/var/backups';
$timestamp = date('Y-m-d_H-i-s');
$backupFile = "$backupDir/backup_$timestamp.tar.gz";

// Check and create the backup directory
if (!is_dir($backupDir)) {
    if (!mkdir($backupDir, 0755, true)) {
        $_SESSION['message'] = "Error: Unable to create backup directory ($backupDir). Check permissions.";
        $_SESSION['message_type'] = "error";
        header("Location: dashboard.php");
        exit;
    }
}

$sourceDir = '/mnt/nas';
exec("tar -czvf $backupFile $sourceDir 2>&1", $output, $return_var);

// Handle the result of the tar command
if ($return_var === 0) {
    $_SESSION['message'] = "Backup created successfully: $backupFile";
    $_SESSION['message_type'] = "success";
    header("Location: dashboard.php");
    $stmt = $pdo->prepare("INSERT INTO backup_schedule (file_name, status, time) VALUES (?, 'completed', ?)");
    $stmt->execute([$backupFile, date('H:i')]);

} else {
    $_SESSION['message'] = "Backup creation failed. Details:\n" . implode("\n", $output);
    $_SESSION['message_type'] = "error";
    header("Location: dashboard.php");
}
header("Location: dashboard.php");
exit();
?>

