<?php
include 'db_connect.php';

$type = $conn->real_escape_string($_POST['type']);
$location = $conn->real_escape_string($_POST['location']);
$severity = $conn->real_escape_string($_POST['severity']);
$start_date = $conn->real_escape_string($_POST['start_date']);
$end_date = !empty($_POST['end_date']) ? "'".$conn->real_escape_string($_POST['end_date'])."'" : "NULL";

$sql = "INSERT INTO Disaster_Events (type, location, severity, start_date, end_date)
        VALUES ('$type', '$location', '$severity', '$start_date', $end_date)";

if ($conn->query($sql)) {
    header("Location: index.php");
    exit();
} else {
    echo "❌ Error: " . $conn->error;
}
?>