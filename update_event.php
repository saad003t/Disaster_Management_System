<?php
include 'db_connect.php';

$id = intval($_POST['id']);
$type = $conn->real_escape_string($_POST['type']);
$location = $conn->real_escape_string($_POST['location']);
$severity = $conn->real_escape_string($_POST['severity']);
$start_date = $conn->real_escape_string($_POST['start_date']);
$end_date = !empty($_POST['end_date']) ? "'".$conn->real_escape_string($_POST['end_date'])."'" : "NULL";

$sql = "UPDATE Disaster_Events SET
            type = '$type',
            location = '$location',
            severity = '$severity',
            start_date = '$start_date',
            end_date = $end_date
        WHERE event_id = $id";

if ($conn->query($sql)) {
    header("Location: index.php");
    exit();
} else {
    echo "❌ Error updating record: " . $conn->error;
}
?>