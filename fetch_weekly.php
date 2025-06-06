<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "task3");
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}

$seven_days_ago = date("Y-m-d", strtotime("-6 days"));
$sql = "SELECT domain, SUM(minutes) as total_minutes FROM website_usage 
        WHERE date >= ? 
        GROUP BY domain";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $seven_days_ago);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row["domain"]] = (int)$row["total_minutes"];
}

echo json_encode($data);
$conn->close();
?>
