<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Chicago');
include 'db_connect.php';
// Get all the scheduled backups that need to run
$stmt = $pdo->query("SELECT * FROM backup_schedule WHERE status = 'pending'");

// Get the current time
$current_time = date('H:i');

while ($backup = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Ensure the row was fetched successfully
    if ($backup) {
        // Check if the scheduled time matches the current time
        if ($backup['time'] === $current_time) {
            // Perform the backup operation
            exec('/var/www/html/backup-script.sh');
            
            // Update status to 'completed' after backup is done
            $update_stmt = $pdo->prepare("UPDATE backup_schedule SET status = 'completed' WHERE id = ?");
            $update_stmt->execute([$backup['id']]);
        }
    }
}

