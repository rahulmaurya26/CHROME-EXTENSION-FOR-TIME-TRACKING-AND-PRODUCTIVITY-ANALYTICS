<?php
// CORS headers to allow cross-origin requests (for frontend JS requests)
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight OPTIONS request for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    http_response_code(200);
    exit();
}

// Connect to MySQL database
$conn = new mysqli("localhost", "root", "", "web_time_tracker");

// Check for database connection errors
if ($conn->connect_error) {
  echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
  exit;
}

// Get 'type' parameter from URL query (default to 'live')
$type = isset($_GET['type']) ? $_GET['type'] : 'live';

// Define mapping of websites to productivity categories
$productivity_map = [
  // Productive sites
  'github.com' => 'Productive',
  'stackoverflow.com' => 'Productive',
  'leetcode.com' => 'Productive',
  'codingplatform.com' => 'Productive',

  // Unproductive sites
  'facebook.com' => 'Unproductive',
  'instagram.com' => 'Unproductive',
  'twitter.com' => 'Unproductive',
  'youtube.com' => 'Unproductive'
];

// Build SQL query based on requested data type
if ($type == 'live') {
  // Today's date for filtering live data
  $today = date('Y-m-d');
  $sql = "SELECT site, SUM(duration) as total_time
          FROM usage_data 
          WHERE DATE(timestamp) = '$today' 
          GROUP BY site 
          ORDER BY total_time DESC";
} else if ($type == 'weekly') {
  // Date range for last 7 days including today
  $week_ago = date('Y-m-d', strtotime('-6 days'));
  $today = date('Y-m-d');
  $sql = "SELECT site, SUM(duration) as total_time
          FROM usage_data 
          WHERE DATE(timestamp) BETWEEN '$week_ago' AND '$today' 
          GROUP BY site 
          ORDER BY total_time DESC";
} else {
  // Invalid type parameter
  echo json_encode(['status' => 'error', 'message' => 'Invalid type']);
  exit;
}

// Execute the query
$result = $conn->query($sql);

$data = [];
if ($result) {
  // Loop through each row returned
  while ($row = $result->fetch_assoc()) {
    $site = $row['site'];
    $category = 'Neutral'; // Default category

    // Check the site against the productivity map to assign category
    foreach ($productivity_map as $key => $cat) {
      if (stripos($site, $key) !== false) { // Case-insensitive substring check
        $category = $cat;
        break;
      }
    }

    // Format the data:
    // - For weekly data, convert total_time from seconds to minutes with 3 decimals
    // - For live data, keep total_time in seconds (integer)
    $data[] = [
      'site' => $site,
      'total_time' => $type == 'weekly' ? round($row['total_time'] / 60, 3) : (int)$row['total_time'],
      'category' => $category
    ];
  }

  // Return JSON encoded array of data
  echo json_encode($data);
} else {
  // Query execution failed
  echo json_encode(['status' => 'error', 'message' => 'Query failed']);
}

// Close the database connection
$conn->close();
?>
