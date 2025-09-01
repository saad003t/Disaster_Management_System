<?php
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_GET['table'])) {
    echo json_encode(['error' => 'No table specified']);
    exit;
}

$table = $_GET['table'];

// Validate table name to prevent SQL injection
$valid_tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
if (!in_array($table, $valid_tables)) {
    echo json_encode(['error' => 'Invalid table']);
    exit;
}

try {
    $stmt = $pdo->query("SELECT * FROM `$table`");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($records);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}