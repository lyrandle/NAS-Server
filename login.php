<?php
session_start();

// Check if there is an error message set in the session
$error_message = "";
if (isset($_SESSION['message'])) {
    $error_message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message after it's displayed
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            display: grid;
            place-items: center;
            height: 100vh;
            margin: 0;
            background-color: #2e402e; 
            font-family: Arial, sans-serif; 
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Network Attached Storage Login</h2>

        <!-- Display Error Message -->
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form action="authenticate.php" method="POST">
            <label for="username">Enter Username</label><br>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Enter Password</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit" name="submit">Login</button>
        </form>
    </div>    
</body>
</html>

