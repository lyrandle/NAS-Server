<?php

session_start();
include 'db_connect.php';
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
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
    <title>NAS Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #2e402e;
	    color: #485848;
        }
   h1 {
            color: #e5e7e5
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border-radius: 5px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: white;
            border-radius: 5px;
        }
        th, td {
            padding: 8px;
            border-radius: 5px;
        }
        th {
            background-color: #f2f2f2;
        }
        .upload-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .upload-btn:hover {
            background-color: #45a049;
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
	.close-popup-checkbox {
	    display: none;
	}

	.close-popup-checkbox:checked ~ .popup-container {
	    display: none;
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
	.form-container {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
         }
	.header-buttons {
	    display: flex; 
	    justify-content: flex-end;
	    gap: 10px;
	    margin: 20px 0;
	}
	.add-user-btn {
	  display: inline-block; 
 	    position: absolute;
	    top: 20px;
	    right: 120px;
	    padding: 10px 20px;
	    font-size: 16px;
	    color: #2e402e;
	    background-color: white;
	    border: none;
	    border-radius: 5px;
	    text-decoration: none; 
	    cursor: pointer; 
	    transition: background-color 0.3s ease; 
	}

	.add-user-btn:hover {
	    background-color: #45a049;
	}
        .logout-button {
	    display: inline-block; 
 	    position: absolute;
	    top: 20px;
	    right: 20px;
	    padding: 10px 20px;
	    font-size: 16px;
	    color: #2e402e;
	    background-color: white;
	    border: none;
	    border-radius: 5px;
	    text-decoration: none; 
	    cursor: pointer; 
	    transition: background-color 0.3s ease; 
	}

	.logout-button:hover {
	    background-color: #c0392b;
	}
	.delete-btn {
            background-color: #2e402e;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .delete-btn:hover {
            background-color: #2e402e;
        }
        .download-btn {
            background-color: #2e402e;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .download-btn:hover {
            background-color: #2e402e;
        }
        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-row .form-container {
            flex: 1;
            max-width: 30%;
            box-sizing: border-box;
            }
        .backups-container{
            background-color: white;
            width: 48%;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .restore-btn {
        position: absolute;
	    top: 20px;
	    left: 20px;
            background-color: white;
            color: #2e402e;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .restore-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

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

   <div class="container">
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    <div class="header-buttons">
        <?php if ($_SESSION['permissions'] === 'admin') : ?>
            <a href="add_user.php"><button class="add-user-btn">Add/Modify User</button></a>
                    <form action="restore_backup.php" method="post" onsubmit="return confirm('Are you sure you want to restore the latest backup?');">
        <button type="submit" class="restore-btn">Restore Latest Backup</button>
        </form>
        <?php endif; ?>
        <a href="logout.php"><button class="logout-button">Logout</button></a>
    </div>

   <!-- Flex Row One -->
    <?php if ($_SESSION['permissions'] === 'admin'): ?>
        <div class="form-row">
            <!-- File Upload Form -->
            <div class="form-container">
                <h3>Select file to upload below:</h3>
                <form action="upload.php" method="post" enctype="multipart/form-data">
   		   <label for="fileToUpload">Select files to upload:</label>
    		   <input type="file" name="fileToUpload[]" id="fileToUpload" multiple> 
                    <button type="submit" class="upload-btn">Upload File</button>
                </form>               
            </div>

            <!-- Folder Creation Form -->
            <div class="form-container">
                <h3>Create a folder:</h3>
                <form action="create_folder.php" method="post">
                    <label for="folderName">Enter folder name:</label>
                    <input type="text" name="folderName" id="folderName" required>
                    <button type="submit">Create Folder</button>
                </form>
            </div>

            <!-- Rename Folder Form -->
            <div class="form-container">
                <h3>Rename a folder:</h3>
                <form action="rename_folder.php" method="POST">
                    <label for="oldName">Old Folder/File Name:</label>
                    <input type="text" id="oldName" name="oldName" required>

                    <label for="newName">New Folder/File Name:</label>
                    <input type="text" id="newName" name="newName" required>

                    <button type="submit">Rename</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Backup Scheduler Form Container -->
    <?php if ($_SESSION['permissions'] === 'admin'): ?>
           <div class="form-row">
        <!-- Backup Schedule Form -->
        <div class="backups-container">
            <h2>Schedule Backup</h2>
            <form action="schedule_backup.php" method="POST">
                <label for="backup_frequency">Backup Frequency:</label><br>
                <select id="backup_frequency" name="backup_frequency" required>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select><br><br>

                <label for="backup_time">Backup Time (HH:MM):</label><br>
                <input type="time" id="backup_time" name="backup_time" required><br><br>

                <button type="submit">Schedule Backup</button>
	    </form>
	    <br>
	    <form action="trigger_backup.php" method="post" onsubmit="return confirm('Are you sure you want to create a new backup?');">
		<button type="submit">Force Back Up</button>
		</br>
	     </form>
	  </div>

        <!-- Upcoming Backups -->
        <div class="backups-container">
            <h2>Upcoming Backups</h2>
		    <?php
		    // Query the backup_schedule table to get the next 5 backups
	$stmt = $pdo->query("SELECT * FROM backup_schedule WHERE status = 'pending' ORDER BY time LIMIT 5");

	// Check if there are any results
	if ($stmt->rowCount() > 0) {
	    // Output the table with results
	    echo "<table><tr><th>Time</th><th>Status</th></tr>";

	    while ($row = $stmt->fetch()) {
		echo "<tr>
		        <td>" . htmlspecialchars($row['time']) . "</td>
		        <td>" . htmlspecialchars($row['status']) . "</td>
		      </tr>";
	    }

	    echo "</table>";
	} else {
	    echo "<p>No upcoming backups found.</p>";
	}
		    ?>
        </div>
    </div>
    
    <?php endif; ?>


          <?php
$directory = "/mnt/nas";

// Check if the directory exists
if (is_dir($directory)) {
    // Open the directory
    if ($handle = opendir($directory)) {
        echo "<div class='file-container'>";
        echo "<h1>Files in $directory</h1>"; 
        
        echo "<table border='1'>
                <tr>
                    <th>File Name</th>
                    <th>Size</th>
                    <th>Last Modified</th>
                    <th>Download</th>";

        // Show 'Delete' column for admin users only
        if ($_SESSION['permissions'] === 'admin') {
            echo "<th>Delete</th>";
        }

        echo "</tr>";

        // Loop through the directory and list files
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $filePath = $directory . '/' . $entry;
                $fileUrl = 'http://192.168.86.65/nas/' . $entry;
                
                // Get file stats
                $fileName = basename($filePath); 
                $fileStats = stat($filePath);
                $fileSize = $fileStats['size']; // File size in bytes
                $lastModified = date("Y-m-d H:i:s", $fileStats['mtime']); // Last modified date

                // Display file details in a table
                echo "<tr>
                        <td>$fileName</td>
                        <td>" . round($fileSize / 1048576, 2) . " MB</td> <!-- Convert bytes to MB -->
                        <td>$lastModified</td>
                        <td><a href='download.php?file=" . urlencode($fileName) . "'><button class='download-btn'>Download</button></a></td>"; 

                // Show delete button for admin users only
                if ($_SESSION['permissions'] === 'admin') {
                    echo "<td><a href='delete.php?file=" . urlencode($fileName) . "'><button class='delete-btn'>Delete</button></a></td>";
                }

                echo "</tr>";
            }
        }
        closedir($handle);
        echo "</table>";
        echo "</div>"; // Close the file-container div
    } else {
        echo "Unable to open directory.";
    }
} else {
    echo "The directory does not exist.";
}
?>

<?php

session_start();

if (!isset($_SESSION['username']) || $_SESSION['permissions'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

// Fetching system stats (disk usage, CPU usage, system logs)

// Disk Usage
$disk_usage = shell_exec('df -h'); 

// CPU Usage
$cpu_usage = shell_exec('top -bn1 | grep "Cpu(s)"');

// Fetch last 10 lines from system logs
$logs = shell_exec('sudo /bin/dmesg | tail -n 10');

// Clean up and format the output
$disk_usage = nl2br(htmlspecialchars($disk_usage));
$cpu_usage = nl2br(htmlspecialchars($cpu_usage));
// Clean up the logs for display
$logs = nl2br(htmlspecialchars($logs));

// If no logs are fetched, show an error message
if (empty($logs)) {
    $logs = "Unable to fetch system logs.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Stats</title>
    <style>
        h1 {
            text-align: center;
        }
        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: white;
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .stats-section {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h1>System Stats</h1>

    <div class="stats-section">
        <table>
            <tr>
                <th>Disk Usage</th>
            </tr>
            <tr>
                <td><pre><?php echo $disk_usage; ?></pre></td>
            </tr>
            <tr>
                <th>CPU Usage</th>
            </tr>
            <tr>
                <td><pre><?php echo $cpu_usage; ?></pre></td>
            </tr>
            <tr>
                <th>System Logs (Last 10 entries)</th>
            </tr>
            <tr>
                <td><pre><?php echo $logs; ?></pre></td>
            </tr>
        </table>
    </div>

</body>
</html>
</body>
</html>

