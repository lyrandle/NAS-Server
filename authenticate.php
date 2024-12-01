<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['message'] = "Please enter both username and password.";
        header('Location: login.php');
        exit();
    }

    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && hash('sha256', $password) === $user['password_hash']) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['permissions'] = $user['permissions'];
            header('Location: dashboard.php');
            exit();
        } else {
            $_SESSION['message'] = "Invalid username or password.";
            header('Location: login.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        header('Location: login.php');
        exit();
    }
}

