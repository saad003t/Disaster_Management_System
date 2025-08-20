<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Disaster Events • Disaster Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Disaster Management System</h1>

    <div class="actions">
        <a href="add_event.php" class="btn">➕ Add Event</a>
        <a href="db_inspector.php" class="btn">🗄️ DB Inspector</a>
        <a href="index.php" class="btn secondary">⟳ Refresh</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Location</th>
                <th>Severity</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $result = $conn->query("SELECT * FROM Disaster_Events ORDER BY start_date DESC");
        if ($result->num_rows > 0): 
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['event_id'] ?></td>
                    <td><?= $row['type'] ?></td>
                    <td><?= $row['location'] ?></td>
                    <td><?= $row['severity'] ?></td>
                    <td><?= $row['start_date'] ?></td>
                    <td><?= $row['end_date'] ?? 'Ongoing' ?></td>
                    <td class="center">
                        <a href="edit_event.php?id=<?= $row['event_id'] ?>">✏️ Edit</a> | 
                        <a href="delete_event.php?id=<?= $row['event_id'] ?>" 
                           onclick="return confirm('Delete this disaster event?');">🗑️ Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7" class="center">No disaster events found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>