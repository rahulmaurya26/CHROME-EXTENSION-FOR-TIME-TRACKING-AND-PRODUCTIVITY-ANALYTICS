<?php
// Handle CORS (Cross-Origin Resource Sharing) to allow requests from any origin
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json"); // Respond with JSON content type

// Handle preflight OPTIONS request (used by browsers before POST requests)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // Respond with OK status
    exit(); // Stop further execution for OPTIONS requests
}

include "db.php";  // Include the database connection file

// Get POST data safely and sanitize
$site = isset($_POST['site']) ? trim($_POST['site']) : '';       // Site URL or hostname
$duration = isset($_POST['duration']) ? (int)$_POST['duration'] : 0; // Duration in seconds (integer)

// Validate received data
if (empty($site) || $duration <= 0) {
    // If site is empty or duration is invalid, return an error response
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
    exit;
}

// Prepare SQL statement to insert usage data securely (prevents SQL injection)
$stmt = $conn->prepare("INSERT INTO usage_data (site, duration) VALUES (?, ?)");
$stmt->bind_param("si", $site, $duration); // Bind parameters: 's' = string, 'i' = integer

// Execute the statement and send response based on success or failure
if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "DB insert failed"]);
}

// Clean up statement and close database connection
$stmt->close();
$conn->close();
?>
