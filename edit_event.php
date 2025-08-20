<?php
include 'db_connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) die("Invalid event ID.");

$result = $conn->query("SELECT * FROM Disaster_Events WHERE event_id = $id LIMIT 1");
if ($result->num_rows === 0) die("Event not found.");
$event = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Disaster Event</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Edit Disaster Event</h1>

    <form action="update_event.php" method="POST" class="form">
        <input type="hidden" name="id" value="<?= $event['event_id'] ?>">

        <label>Type</label>
        <select name="type" required>
            <option value="Flood" <?= $event['type']=='Flood'?'selected':'' ?>>Flood</option>
            <option value="Earthquake" <?= $event['type']=='Earthquake'?'selected':'' ?>>Earthquake</option>
            <option value="Wildfire" <?= $event['type']=='Wildfire'?'selected':'' ?>>Wildfire</option>
            <option value="Hurricane" <?= $event['type']=='Hurricane'?'selected':'' ?>>Hurricane</option>
            <option value="Tornado" <?= $event['type']=='Tornado'?'selected':'' ?>>Tornado</option>
            <option value="Tsunami" <?= $event['type']=='Tsunami'?'selected':'' ?>>Tsunami</option>
        </select>

        <label>Location</label>
        <input type="text" name="location" value="<?= $event['location'] ?>" required>

        <label>Severity</label>
        <select name="severity" required>
            <option value="Low" <?= $event['severity']=='Low'?'selected':'' ?>>Low</option>
            <option value="Medium" <?= $event['severity']=='Medium'?'selected':'' ?>>Medium</option>
            <option value="High" <?= $event['severity']=='High'?'selected':'' ?>>High</option>
            <option value="Severe" <?= $event['severity']=='Severe'?'selected':'' ?>>Severe</option>
            <option value="Extreme" <?= $event['severity']=='Extreme'?'selected':'' ?>>Extreme</option>
        </select>

        <label>Start Date</label>
        <input type="date" name="start_date" value="<?= $event['start_date'] ?>" required>

        <label>End Date (if applicable)</label>
        <input type="date" name="end_date" value="<?= $event['end_date'] ?>">

        <button type="submit" class="btn">Update Event</button>
    </form>

    <p><a href="index.php">← Back to Events</a></p>
</body>
</html>