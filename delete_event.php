<?php
include 'db_connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) die("Invalid event ID.");

$sql = "DELETE FROM Disaster_Events WHERE event_id = $id";
if ($conn->query($sql)) {
    header("Location: index.php");
    exit();
} else {
    echo "❌ Error deleting record: " . $conn->error;
}
?>