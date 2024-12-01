<?php

include 'db_connect.php';
session_start();

// Check if the user is logged in and has admin permissions
if (!isset($_SESSION['username']) || $_SESSION['permissions'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

// Fetch all users from the database
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
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
            max-width: 600px;
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #45a049;
        }

        .delete-button {
            background-color: #f44336;
            color: white;s
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
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
    <h2>User List</h2>

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Permissions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                <!-- Edit Button -->
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['permissions']); ?></td>
                    <td>
                        <a href="update_user.php?id=<?= htmlspecialchars($user['id']); ?>">
                            <button>Edit</button>
                        </a>
                        <!-- Delete Button -->
                        <form action="delete_user.php" method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="add_user.php">
        <button class="back-button">Add User</button>
    </a>
    <a href="dashboard.php">
        <button class="back-button">Back to Dashboard</button>
    </a>
</div>

</body>
</html>

