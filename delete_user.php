<?php
// Include database connection
include 'db_connect.php';
session_start();

// Check if the user is logged in and has admin permissions
if (!isset($_SESSION['username']) || $_SESSION['permissions'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

// Check if a user_id is provided to delete
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Prepare the SQL query to delete the user
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    // Redirect to the user list page
    header('Location: user_list.php');
    exit();
} else {
    // If no user_id is provided, redirect to user list
    header('Location: user_list.php');
    exit();
}
?>

