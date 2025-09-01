<?php
include 'db_connect.php';

// Get shelter information
$shelters = $pdo->query("
    SELECT s.*, e.type as event_type, e.location as event_location 
    FROM Shelters s 
    JOIN Disaster_Events e ON s.event_id = e.event_id
")->fetchAll(PDO::FETCH_ASSOC);

// Get transportation information
$transportation = $pdo->query("
    SELECT t.*, e.type as event_type, e.location as event_location 
    FROM Transportation t 
    JOIN Disaster_Events e ON t.event_id = e.event_id
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Disaster Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Disaster Management System</div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="alerts.php">Alerts</a></li>
                <li><a href="resources.php">Resources</a></li>
                <li><a href="volunteers.php">Volunteers</a></li>
                <li><a href="reports.php" class="active">Reports</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="admin_login.php">Admin</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Reports & Analytics</h1>
            <p>Access critical information and statistics about disaster response operations</p>
        </div>
    </section>

    <section class="content">
        <h1>Reports & Analytics</h1>
        
        <!-- Add this new section before other report sections -->
        <div class="report-section">
            <h2>Shelter Occupancy Status</h2>
            <table>
                <thead>
                    <tr>
                        <th>Shelter Name</th>
                        <th>Location</th>
                        <th>Total Capacity</th>
                        <th>Current Occupancy</th>
                        <th>Occupancy Rate</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($shelters) > 0): ?>
                        <?php foreach ($shelters as $shelter): 
                            $occupancy = ($shelter['capacity'] > 0) ? 
                                round(($shelter['current_occupancy'] / $shelter['capacity'] * 100), 1) : 0;
                            
                            $statusClass = $occupancy > 90 ? 'high-occupancy' : 
                                         ($occupancy > 75 ? 'medium-occupancy' : 'normal-occupancy');
                            $statusText = $occupancy > 90 ? 'Critical' : 
                                        ($occupancy > 75 ? 'High' : 'Normal');
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($shelter['name']); ?></td>
                            <td><?php echo htmlspecialchars($shelter['location']); ?></td>
                            <td><?php echo htmlspecialchars($shelter['capacity']); ?></td>
                            <td><?php echo htmlspecialchars($shelter['current_occupancy']); ?></td>
                            <td><?php echo number_format($occupancy, 1); ?>%</td>
                            <td>
                                <span class="status-badge <?php echo $statusClass; ?>">
                                    <?php echo $statusText; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No shelter occupancy information available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="report-section">
            <h2>Shelter Information</h2>
            <table>
                <thead>
                    <tr>
                        <th>Shelter Name</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Current Occupancy</th>
                        <th>Associated Event</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($shelters) > 0): ?>
                        <?php foreach ($shelters as $shelter): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($shelter['name']); ?></td>
                            <td><?php echo htmlspecialchars($shelter['location']); ?></td>
                            <td><?php echo htmlspecialchars($shelter['capacity']); ?></td>
                            <td><?php echo htmlspecialchars($shelter['current_occupancy']); ?></td>
                            <td><?php echo htmlspecialchars($shelter['event_type']); ?> in <?php echo htmlspecialchars($shelter['event_location']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No shelter information available at this time.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="report-section">
            <h2>Transportation Information</h2>
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Driver Name</th>
                        <th>Status</th>
                        <th>Associated Event</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($transportation) > 0): ?>
                        <?php foreach ($transportation as $transport): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transport['type']); ?></td>
                            <td><?php echo htmlspecialchars($transport['driver_name']); ?></td>
                            <td>
                                <span class="status-badge <?php echo $transport['availability'] ? 'available' : 'unavailable'; ?>">
                                    <?php echo $transport['availability'] ? 'Available' : 'Unavailable'; ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($transport['event_type']); ?> in <?php echo htmlspecialchars($transport['event_location']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No transportation information available at this time.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Disaster Management System. <a href="#">Privacy Policy</a></p>
    </footer>
</body>
</html>

<?php
// Keep these utility functions
function canAcceptMoreResidents($shelter_id, $new_residents_count) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT calculate_shelter_occupancy(?) as occupancy");
        $stmt->execute([$shelter_id]);
        $current_occupancy = $stmt->fetchColumn();
        return ($current_occupancy + $new_residents_count) <= 100;
    } catch (PDOException $e) {
        return false;
    }
}

function isResourceAvailable($resource_id, $requested_quantity) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT get_available_quantity(?) as available");
        $stmt->execute([$resource_id]);
        $available = $stmt->fetchColumn();
        return $available >= $requested_quantity;
    } catch (PDOException $e) {
        return false;
    }
}

function getActiveEventsWithPersonnel() {
    global $pdo;
    try {
        $query = "
            SELECT 
                e.*, 
                count_active_personnel(e.event_id) as personnel_count
            FROM Disaster_Events e
            WHERE is_event_active(e.event_id) = TRUE
        ";
        return $pdo->query($query)->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

// Replace the existing getShelterOccupancy function

function getShelterOccupancy($shelter_id) {
    global $pdo;
    try {
        // Direct calculation in PHP as backup if MySQL function fails
        $stmt = $pdo->prepare("
            SELECT (current_occupancy / capacity * 100) as occupancy_rate
            FROM Shelters 
            WHERE shelter_id = ?
        ");
        $stmt->execute([$shelter_id]);
        $result = $stmt->fetchColumn();
        return $result !== false ? round($result, 1) : 0;
    } catch (PDOException $e) {
        return 0;
    }
}
?>