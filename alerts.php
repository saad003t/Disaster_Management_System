<?php
include 'db_connect.php';

// Get all disaster events
$events = $pdo->query("SELECT * FROM Disaster_Events ORDER BY start_date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts - Disaster Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
    <nav>
        <div class="logo">Disaster Management System</div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="alerts.php" class="active">Alerts</a></li>
            <li><a href="resources.php">Resources</a></li>
            <li><a href="volunteers.php">Volunteers</a></li>
            <li><a href="reports.php">Reports</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="admin_login.php">Admin</a></li>
        </ul>
    </nav>
</header>

    <section class="hero">
        <div class="hero-content">
            <h1>Disaster Alerts</h1>
            <p>Stay informed about current disaster events and emergency situations</p>
        </div>
    </section>

    <section class="content">
        <h1>Current Disaster Alerts</h1>
        
        <div class="alert-list">
            <?php if (count($events) > 0): ?>
                <?php foreach ($events as $event): ?>
                <div class="alert-card">
                    <h3><?php echo htmlspecialchars($event['type']); ?> in <?php echo htmlspecialchars($event['location']); ?></h3>
                    <p><strong>Severity:</strong> <?php echo htmlspecialchars($event['severity']); ?></p>
                    <p><strong>Start Date:</strong> <?php echo htmlspecialchars($event['start_date']); ?></p>
                    <?php if ($event['end_date']): ?>
                    <p><strong>End Date:</strong> <?php echo htmlspecialchars($event['end_date']); ?></p>
                    <?php else: ?>
                    <p><strong>Status:</strong> <span style="color: #e74c3c; font-weight: bold;">Ongoing</span></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data-message">
                    <p>No active alerts at this time. Check back later for updates.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Disaster Management System. <a href="#">Privacy Policy</a></p>
    </footer>
</body>
</html>