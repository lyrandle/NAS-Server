<?php

session_start();
// Include the database connection
include 'db_connect.php'; // Contains the connection logic

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $permissions = trim($_POST['permissions']); // Either 'user' or 'admin'

    // Basic validation
    if (empty($username) || empty($password) || empty($permissions)) {
        $_SESSION['message'] = "Error: all fields required";
        $_SESSION['message_type'] = "error";
        header('Location: add_user.php');
        exit();
    }

    // Hash the password before storing it
    $passwordHash = hash('sha256', $password); // You can use a more secure hashing algorithm like bcrypt

    try {
        // Prepare SQL to insert the new user into the database
        $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, permissions) VALUES (?, ?, ?)');
        
        // Execute the prepared statement
        $stmt->execute([$username, $passwordHash,$permissions]);

        // Start session and store user data
        $_SESSION['message'] = "User added successfully!";
        $_SESSION['message_type'] = "success";
        header('Location: add_user.php');
        exit();
    } catch (PDOException $e) {
        // Handle any errors that occur during the database operation
        $_SESSION['message'] = "Error: Failed to add user";
        $_SESSION['message_type'] = "error";
        header('Location: add_user.php'); // Redirect back to the add user page
        exit();
    }
}
?>

