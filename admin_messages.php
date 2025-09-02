<?php
include 'admin_auth.php';
include 'db_connect.php';

// Initialize messages array
$messages = [];

try {
    // Get all messages ordered by newest first
    $messages = $pdo->query("
        SELECT *, DATE_FORMAT(submission_date, '%Y-%m-%d %H:%i') as formatted_date 
        FROM Contact_Messages 
        ORDER BY submission_date DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Unable to retrieve messages. Please make sure the database is properly set up.";
}

// Mark message as read
if (isset($_POST['mark_read']) && isset($_POST['message_id'])) {
    try {
        $stmt = $pdo->prepare("UPDATE Contact_Messages SET status = 'read' WHERE message_id = ?");
        $stmt->execute([$_POST['message_id']]);
        header('Location: admin_messages.php');
        exit;
    } catch (PDOException $e) {
        $error = "Unable to update message status.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messages - Disaster Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .message-grid {
            display: grid;
            gap: 20px;
            padding: 20px;
        }
        .message-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .unread {
            background: #f0f7ff;
            border-left: 4px solid #0066cc;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
        }
        .unread-badge {
            background: #0066cc;
            color: white;
        }
        .read-badge {
            background: #e0e0e0;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Admin Dashboard</div>
            <ul>
                <li><a href="admin_dashboard.php">Events</a></li>
                <li><a href="admin_messages.php" class="active">Messages</a></li>
                <li><a href="overview.php">DB Overview</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="content">
        <h1>Contact Messages</h1>
        
        <?php if (isset($error)): ?>
            <div class="error-message" style="background: #ffe6e6; color: #cc0000; padding: 10px; margin: 20px; border-radius: 4px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <div class="message-grid">
            <?php if (count($messages) > 0): ?>
                <?php foreach ($messages as $message): ?>
                    <div class="message-card <?php echo $message['status'] === 'unread' ? 'unread' : ''; ?>">
                        <div class="message-header">
                            <h3><?php echo htmlspecialchars($message['subject']); ?></h3>
                            <span class="status-badge <?php echo $message['status'] === 'unread' ? 'unread-badge' : 'read-badge'; ?>">
                                <?php echo ucfirst($message['status']); ?>
                            </span>
                        </div>
                        <p><strong>From:</strong> <?php echo htmlspecialchars($message['name']); ?> 
                           (<?php echo htmlspecialchars($message['email']); ?>)</p>
                        <p><strong>Date:</strong> <?php echo $message['formatted_date']; ?></p>
                        <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                        
                        <?php if ($message['status'] === 'unread'): ?>
                            <form method="post" style="margin-top: 10px;">
                                <input type="hidden" name="message_id" value="<?php echo $message['message_id']; ?>">
                                <button type="submit" name="mark_read" class="submit-btn">Mark as Read</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="message-card">
                    <p>No messages found.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Disaster Management System. <a href="#">Privacy Policy</a></p>
    </footer>
</body>
</html>