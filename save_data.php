<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "task3");
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

$data = json_decode(file_get_contents("php://input"), true);
$domain = $data["domain"] ?? null;
$minutes = $data["minutes"] ?? null;

if ($domain === null || $minutes === null) {
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

$date = date("Y-m-d");

$stmt = $conn->prepare("INSERT INTO website_usage (domain, minutes, date)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE minutes = minutes + VALUES(minutes)");
$stmt->bind_param("sis", $domain, $minutes, $date);
$stmt->execute();

echo json_encode(["success" => true]);
$conn->close();
?>
