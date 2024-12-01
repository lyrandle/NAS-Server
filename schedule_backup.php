<?php
session_start();
include 'db_connect.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $backup_frequency = trim($_POST['backup_frequency']);
    $backup_time = trim($_POST['backup_time']);

    // Validate inputs
    if (empty($backup_frequency) || empty($backup_time)) {
        $_SESSION['message'] = "Error: Both frequency and time are required.";
        $_SESSION['message_type'] = "error";
        header('Location: dashboard.php');
        exit();
    }

    try {
        // Insert the backup schedule into the database
        $stmt = $pdo->prepare("INSERT INTO backup_schedule (frequency, time) VALUES (?, ?)");
        $stmt->execute([$backup_frequency, $backup_time]);

        $_SESSION['message'] = "Backup scheduled successfully!";
        $_SESSION['message_type'] = "success";
        header('Location: dashboard.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: Failed to schedule backup.";
        $_SESSION['message_type'] = "error";
        header('Location: dashboard.php');
        exit();
    }
}
?>

