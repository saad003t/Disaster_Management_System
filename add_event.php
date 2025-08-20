<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Disaster Event</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Add New Disaster Event</h1>

    <form action="insert_event.php" method="POST" class="form">
        <label>Type</label>
        <select name="type" required>
            <option value="">Select Type</option>
            <option value="Flood">Flood</option>
            <option value="Earthquake">Earthquake</option>
            <option value="Wildfire">Wildfire</option>
            <option value="Hurricane">Hurricane</option>
            <option value="Tornado">Tornado</option>
            <option value="Tsunami">Tsunami</option>
        </select>

        <label>Location</label>
        <input type="text" name="location" required>

        <label>Severity</label>
        <select name="severity" required>
            <option value="">Select Severity</option>
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
            <option value="Severe">Severe</option>
            <option value="Extreme">Extreme</option>
        </select>

        <label>Start Date</label>
        <input type="date" name="start_date" required>

        <label>End Date (if applicable)</label>
        <input type="date" name="end_date">

        <button type="submit" class="btn">Add Event</button>
    </form>

    <p><a href="index.php">← Back to Events</a></p>
</body>
</html>