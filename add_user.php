<?php
// Start session
session_start();

// Include the database connection
include 'db_connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['permissions'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

// Fetch user data if editing an existing user
$user = null;
if (isset($_GET['edit'])) {
    $user_id = $_GET['edit'];

    // Fetch the user data
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        // User not found
        header('Location: user_list.php');
        exit();
    }
}
// Check for error or success messages passed via the session
if (isset($_SESSION['message']) && $_SESSION['message'] != "") {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    // Clear the message after showing it
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
} else {
    $message = "";
    $message_type = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($user) ? 'Modify' : 'Add'; ?> User</title>
    <style>
        body {
            display: grid;
            place-items: center;
            height: 100vh;
            margin: 0;
            background-color: #2e402e;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
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
	/* Hide the entire container when the checkbox is checked */
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

	/* When the checkbox is checked, hide the popup-container */
	.close-popup-checkbox {
	    display: none; /* Hide the checkbox itself */
	}

	.close-popup-checkbox:checked ~ .popup-container {
	    display: none;
	}

	/* Popup styling */
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

	/* Different colors for success and error */
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

	/* Close button styling */
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

<div class="container">
    <h2><?php echo isset($user) ? 'Modify' : 'Add'; ?> User</h2>

    <!-- Form for adding or modifying a user -->
    <form action="add_user_process.php" method="POST">
        <!-- If editing, pass user_id in hidden field -->
        <?php if (isset($user)): ?>
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
        <?php endif; ?>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo isset($user) ? $user['username'] : ''; ?>" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">

        <label for="permissions">Permissions:</label>
        <select id="permissions" name="permissions" required>
            <option value="admin" <?php echo (isset($user) && $user['permissions'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="read" <?php echo (isset($user) && $user['permissions'] === 'user') ? 'selected' : ''; ?>>User</option>
        </select>

        <button type="submit"><?php echo isset($user) ? 'Update' : 'Add'; ?> User</button>
    </form>


<a href="user_list.php">User List</a>
    
    <a href="dashboard.php">
        <button class="back-button">Back to Dashboard</button>
    </a>
</div>

    <!-- Popup for success/error messages -->
    <?php if ($message != ""): ?>
        <div class="popup-container">
            <div class="popup <?php echo $message_type; ?>">
                <p><?php echo htmlspecialchars($message); ?></p>
                <a href="#" class="close-btn" onclick="document.querySelector('.popup-container').style.display='none';">Close</a>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

