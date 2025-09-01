<?php
include 'db_connect.php';

// Get statistics for dashboard with correct queries
$regionsCovered = $pdo->query("SELECT COUNT(DISTINCT location) FROM Disaster_Events")->fetchColumn();
$activeAlerts = $pdo->query("SELECT COUNT(*) FROM Disaster_Events WHERE end_date IS NULL OR end_date >= CURDATE()")->fetchColumn();
$volunteersCount = $pdo->query("SELECT COUNT(*) FROM Personnel WHERE status = 'Assigned'")->fetchColumn();
$reliefItems = $pdo->query("SELECT COALESCE(SUM(quantity_used), 0) FROM Resource_Allocation")->fetchColumn();

// Get active events
$events = $pdo->query("SELECT * FROM Disaster_Events WHERE end_date IS NULL OR end_date >= CURDATE()")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disaster Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Disaster Management System</div>
            <ul>
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="alerts.php">Alerts</a></li>
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
            <h1>Prepared. Responsive. Protected.</h1>
            <p>Swift coordination, resource allocation, and real-time response to disasters.</p>
            <a href="alerts.php" class="cta-button">View Active Alerts</a>
        </div>
    </section>

    <section class="stats">
        <div class="stat-item">
            <h2><?php echo $regionsCovered; ?></h2>
            <p>Regions Covered</p>
        </div>
        <div class="stat-item">
            <h2><?php echo $activeAlerts; ?></h2>
            <p>Active Alerts</p>
        </div>
        <div class="stat-item">
            <h2><?php echo $volunteersCount; ?></h2>
            <p>Volunteers On Ground</p>
        </div>
        <div class="stat-item">
            <h2><?php echo $reliefItems ? $reliefItems : '0'; ?>+</h2>
            <p>Relief Items Distributed</p>
        </div>
    </section>

    <section class="features">
        <h2>Our Key Features</h2>
        <div class="feature-grid">
            <div class="feature">
                <h3>Early Warning</h3>
                <p>Automated alerts to communities and teams.</p>
            </div>
            <div class="feature">
                <h3>Resource Allocation</h3>
                <p>Track and distribute emergency supplies.</p>
            </div>
            <div class="feature">
                <h3>Volunteer Coordination</h3>
                <p>Assign and monitor volunteers across zones.</p>
            </div>
            <div class="feature">
                <h3>Reports & Analytics</h3>
                <p>Generate impact insights for planning.</p>
            </div>
        </div>
    </section>

    <section class="portal">
        <h2>One-Stop Emergency Portal</h2>
        <div class="portal-options">
            <a href="#" class="portal-btn">Log in / Sign Up</a>
            <a href="reports.php" class="portal-btn">Submit Report</a>
            <a href="#" class="portal-btn">Access Dashboard</a>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Disaster Management System. <a href="#">Privacy Policy</a></p>
    </footer>
</body>
</html>