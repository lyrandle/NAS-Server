<?php
session_start();
include 'db_connect.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user inputs
    $id = intval($_POST['id']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); 
    $permissions = trim($_POST['permissions']); //'read', 'write', or 'admin'


    if (empty($id) || empty($username) || empty($permissions)) {
        $_SESSION['message'] = "Error: ID, username, and permissions are required.";
        $_SESSION['message_type'] = "error";
        header('Location: update_user.php?id=' . $id); 
        exit();
    }

    try {
        // Prepare the base SQL for updating the user
        $sql = "UPDATE users SET username = ?, permissions = ?";
        $params = [$username, $permissions];

        // If a new password is provided, hash it and include it in the query
        if (!empty($password)) {
            $passwordHash = hash('sha256', $password);
            $sql .= ", password_hash = ?";
            $params[] = $passwordHash;
        }

        // Complete the SQL with the WHERE clause to specify the user
        $sql .= " WHERE id = ?";
        $params[] = $id;

        // Prepare and execute the SQL
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Provide feedback to the user
        if ($stmt->rowCount() > 0) {
            header('Location: user_list.php'); 
        } else {
            $_SESSION['message'] = "No changes made or invalid user ID.";
            $_SESSION['message_type'] = "error";
        }

        header('Location: user_list.php');
        exit();

    } catch (PDOException $e) {
        // Handle any errors that occur during the database operation
        $_SESSION['message'] = "Error: Failed to update user.";
        $_SESSION['message_type'] = "error";
        header('Location: update_user.php?id=' . $id);
        exit();
    }
}
?>

