<?php
session_start();
include 'db_connect.php';

// Check if there's a session message for success or error
if (isset($_SESSION['message']) && $_SESSION['message'] != "") {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    // Clear the session message after showing it
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
} else {
    $message = "";
    $message_type = "";
}


if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['message'] = "Error: User not found.";
        $_SESSION['message_type'] = "error";
        header('Location: user_list.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <style>
        body {
            display: grid;
            place-items: center;
            height: 100vh;
            margin: 0;
            background-color: #2e402e;
            font-family: Arial, sans-serif;
        }

        .form-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
a {
            display: block;
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50;
        }

        a:hover {
            color: #45a049;
        }
        .popup-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .popup {
            padding: 20px;
            width: 300px;
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            text-align: center;
            position: relative;
        }

        .popup.success {
            border-color: #4CAF50;
            background-color: #d4edda;
            color: #155724;
        }

        .popup.error {
            border-color: #f44336;
            background-color: #f8d7da;
            color: #721c24;
        }

        .popup .close-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .popup .close-btn:hover {
            background-color: #0056b3;
        }
                .back-button {
            margin-top: 20px;
            background-color: #4CAF50;
            border: none;
            padding: 10px 15px;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Update User</h2>
        
        <!-- Display the popup if there is a message -->
        <?php if ($message != ""): ?>
            <div class="popup-container">
                <div class="popup <?php echo $message_type; ?>">
                    <p><?php echo htmlspecialchars($message); ?></p>
                    <a href="#" class="close-btn" onclick="document.querySelector('.popup-container').style.display='none';">Close</a>
                </div>
            </div>
        <?php endif; ?>
        
        <form action="update_user_process.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br><br>
            <label for="password">New Password (leave blank to keep current):</label><br>
            <input type="password" id="password" name="password"><br><br>
            <label for="permissions">Permissions:</label><br>
            <select id="permissions" name="permissions" required>
                <option value="read" <?php echo $user['permissions'] === 'read' ? 'selected' : ''; ?>>Read</option>
                <option value="admin" <?php echo $user['permissions'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select><br><br>
            <button type="submit" class="back-button">Update User</button>
    <a href="user_list.php">User List</a>
    
    <a href="dashboard.php">
        <button class="back-button">Back to Dashboard</button>
    </a>
    </div>

</div>
</body>
</html>

