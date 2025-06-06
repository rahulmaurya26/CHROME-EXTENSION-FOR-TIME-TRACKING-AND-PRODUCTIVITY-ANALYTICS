<?php
// Database connection settings
$host = "localhost";   // Database server hostname
$user = "root";        // Database username
$pass = "";            // Database password (empty here)
$db = "web_time_tracker"; // Database name

// Create a new connection to the MySQL database using mysqli
$conn = new mysqli($host, $user, $pass, $db);

// Check if the connection was successful
if ($conn->connect_error) {
    // If connection failed, stop the script and show the error message
    die("Connection failed: " . $conn->connect_error);
}
?>
