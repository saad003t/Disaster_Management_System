<?php
include 'db_connect.php';

// Get all personnel
$personnel = $pdo->query("SELECT * FROM Personnel")->fetchAll(PDO::FETCH_ASSOC);

// Get personnel assignments
$assignments = $pdo->query("
    SELECT pa.*, p.name as personnel_name, e.type as event_type, e.location, pa.assigned_role 
    FROM Personnel_Assignment pa 
    JOIN Personnel p ON pa.personnel_id = p.personnel_id 
    JOIN Disaster_Events e ON pa.event_id = e.event_id
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteers - Disaster Management System</title>
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
                <li><a href="volunteers.php" class="active">Volunteers</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="admin_login.php">Admin</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Volunteer Management</h1>
            <p>Coordinate and manage personnel for effective disaster response</p>
        </div>
    </section>

    <section class="content">
        <h1>Volunteer Management</h1>
        
        <div class="volunteer-section">
            <h2>All Personnel</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Contact Number</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($personnel) > 0): ?>
                        <?php foreach ($personnel as $person): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($person['name']); ?></td>
                            <td><?php echo htmlspecialchars($person['role']); ?></td>
                            <td><?php echo htmlspecialchars($person['contact_number']); ?></td>
                            <td>
                                <span class="status-badge <?php echo strtolower(htmlspecialchars($person['status'])); ?>">
                                    <?php echo htmlspecialchars($person['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No personnel records available at this time.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="volunteer-section">
            <h2>Personnel Assignments</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Event</th>
                        <th>Location</th>
                        <th>Role</th>
                        <th>Assignment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($assignments) > 0): ?>
                        <?php foreach ($assignments as $assignment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($assignment['personnel_name']); ?></td>
                            <td><?php echo htmlspecialchars($assignment['event_type']); ?></td>
                            <td><?php echo htmlspecialchars($assignment['location']); ?></td>
                            <td><?php echo htmlspecialchars($assignment['assigned_role']); ?></td>
                            <td><?php echo htmlspecialchars($assignment['assignment_date']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No personnel assignments available at this time.</td>
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