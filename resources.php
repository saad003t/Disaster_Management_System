<?php
include 'db_connect.php';

// Get all resources
$resources = $pdo->query("SELECT * FROM Resources")->fetchAll(PDO::FETCH_ASSOC);

// Get resource allocations
$allocations = $pdo->query("
    SELECT ra.*, r.name as resource_name, e.type as event_type, e.location 
    FROM Resource_Allocation ra 
    JOIN Resources r ON ra.resource_id = r.resource_id 
    JOIN Disaster_Events e ON ra.event_id = e.event_id
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resources - Disaster Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Disaster Management System</div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="alerts.php">Alerts</a></li>
                <li><a href="resources.php" class="active">Resources</a></li>
                <li><a href="volunteers.php">Volunteers</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="admin_login.php">Admin</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Resource Management</h1>
            <p>Track and manage critical resources for disaster response and recovery</p>
        </div>
    </section>

    <section class="content">
        <h1>Resource Management</h1>
        
        <div class="resource-section">
            <h2>Available Resources</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Quantity Available</th>
                        <th>Unit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($resources) > 0): ?>
                        <?php foreach ($resources as $resource): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($resource['name']); ?></td>
                            <td><?php echo htmlspecialchars($resource['type']); ?></td>
                            <td><?php echo htmlspecialchars($resource['quantity_available']); ?></td>
                            <td><?php echo htmlspecialchars($resource['unit']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No resources available at this time.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="resource-section">
            <h2>Resource Allocations</h2>
            <table>
                <thead>
                    <tr>
                        <th>Resource</th>
                        <th>Event</th>
                        <th>Location</th>
                        <th>Quantity Used</th>
                        <th>Allocation Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($allocations) > 0): ?>
                        <?php foreach ($allocations as $allocation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($allocation['resource_name']); ?></td>
                            <td><?php echo htmlspecialchars($allocation['event_type']); ?></td>
                            <td><?php echo htmlspecialchars($allocation['location']); ?></td>
                            <td><?php echo htmlspecialchars($allocation['quantity_used']); ?></td>
                            <td><?php echo htmlspecialchars($allocation['allocation_date']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No resource allocations available at this time.</td>
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
// Check shelter capacity before adding new residents
function canAcceptMoreResidents($shelter_id, $new_residents_count) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT calculate_shelter_occupancy(?) as occupancy");
    $stmt->execute([$shelter_id]);
    $current_occupancy = $stmt->fetchColumn();
    
    return ($current_occupancy + $new_residents_count) <= 100;
}

// Check resource availability
function isResourceAvailable($resource_id, $requested_quantity) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT get_available_quantity(?) as available");
    $stmt->execute([$resource_id]);
    $available = $stmt->fetchColumn();
    
    return $available >= $requested_quantity;
}

// Get active events with their personnel count
function getActiveEventsWithPersonnel() {
    global $pdo;
    
    $query = "
        SELECT 
            e.*, 
            count_active_personnel(e.event_id) as personnel_count
        FROM Disaster_Events e
        WHERE is_event_active(e.event_id) = TRUE
    ";
    
    return $pdo->query($query)->fetchAll();
}
?>