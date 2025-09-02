<?php

include 'admin_auth.php';
include 'db_connect.php';

$success_message = '';
$error_message = '';

// Handle new event submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO Disaster_Events (type, location, severity, start_date, end_date)
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $_POST['type'],
            $_POST['location'],
            $_POST['severity'],
            $_POST['start_date'],
            !empty($_POST['end_date']) ? $_POST['end_date'] : null
        ]);
        
        $success_message = "Event added successfully!";
    } catch (PDOException $e) {
        $error_message = "Error adding event: " . $e->getMessage();
    }
}

// Handle edit event submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_event'])) {
    try {
        $stmt = $pdo->prepare("
            UPDATE Disaster_Events 
            SET type = ?, location = ?, severity = ?, start_date = ?, end_date = ?
            WHERE event_id = ?
        ");
        
        $stmt->execute([
            $_POST['type'],
            $_POST['location'],
            $_POST['severity'],
            $_POST['start_date'],
            !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            $_POST['event_id']
        ]);
        
        $success_message = "Event updated successfully!";
    } catch (PDOException $e) {
        $error_message = "Error updating event: " . $e->getMessage();
    }
}

// Get event details for editing
$edit_event = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM Disaster_Events WHERE event_id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_event = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get existing events
$events = $pdo->query("SELECT * FROM Disaster_Events ORDER BY start_date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Disaster Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .admin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .admin-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .nav-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .nav-tab {
            padding: 10px 20px;
            background: #f5f5f5;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .nav-tab.active {
            background: #2c3e50;
            color: white;
        }
        .edit-btn {
            background: #3498db;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9em;
        }

        .edit-btn:hover {
            background: #2980b9;
        }

        .cancel-btn {
            background: #95a5a6;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }

        .cancel-btn:hover {
            background: #7f8c8d;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Admin Dashboard</div>
            <ul>
                <li><a href="admin_dashboard.php" class="active">Events</a></li>
                <li><a href="admin_messages.php">Messages</a></li>
                <li><a href="overview.php">DB Overview</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="content">
        <h1>Admin Dashboard</h1>
        
        <?php if ($success_message): ?>
            <div class="success-message" style="background: #e6ffe6; color: #006600; padding: 10px; margin: 20px; border-radius: 4px;">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="error-message" style="background: #ffe6e6; color: #cc0000; padding: 10px; margin: 20px; border-radius: 4px;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <div class="admin-grid">
            <div class="admin-card">
                <h2><?php echo $edit_event ? 'Edit Event' : 'Add New Event'; ?></h2>
                <form method="post">
                    <?php if ($edit_event): ?>
                        <input type="hidden" name="event_id" value="<?php echo $edit_event['event_id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="type">Event Type</label>
                        <input type="text" id="type" name="type" 
                            value="<?php echo $edit_event ? htmlspecialchars($edit_event['type']) : ''; ?>" 
                            required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" 
                            value="<?php echo $edit_event ? htmlspecialchars($edit_event['location']) : ''; ?>" 
                            required>
                    </div>
                    <div class="form-group">
                        <label for="severity">Severity</label>
                        <select id="severity" name="severity" required>
                            <?php
                            $severities = ['Low', 'Medium', 'High', 'Severe', 'Extreme'];
                            foreach ($severities as $severity):
                                $selected = ($edit_event && $edit_event['severity'] === $severity) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $severity; ?>" <?php echo $selected; ?>>
                                    <?php echo $severity; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" id="start_date" name="start_date" 
                            value="<?php echo $edit_event ? $edit_event['start_date'] : ''; ?>" 
                            required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" id="end_date" name="end_date" 
                            value="<?php echo $edit_event ? $edit_event['end_date'] : ''; ?>">
                    </div>
                    <button type="submit" name="<?php echo $edit_event ? 'edit_event' : 'add_event'; ?>" class="submit-btn">
                        <?php echo $edit_event ? 'Update Event' : 'Add Event'; ?>
                    </button>
                    <?php if ($edit_event): ?>
                        <a href="admin_dashboard.php" class="cancel-btn" style="margin-left: 10px;">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="admin-card">
                <h2>Recent Events</h2>
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Severity</th>
                            <th>Start Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($event['type']); ?></td>
                                <td><?php echo htmlspecialchars($event['location']); ?></td>
                                <td><?php echo htmlspecialchars($event['severity']); ?></td>
                                <td><?php echo htmlspecialchars($event['start_date']); ?></td>
                                <td>
                                    <?php
                                    echo (!$event['end_date'] || $event['end_date'] >= date('Y-m-d')) 
                                        ? '<span style="color: green;">Active</span>' 
                                        : '<span style="color: red;">Ended</span>';
                                    ?>
                                </td>
                                <td>
                                    <a href="?edit=<?php echo $event['event_id']; ?>" class="edit-btn">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>
</html>