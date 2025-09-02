<?php
include 'db_connect.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO Contact_Messages (name, email, subject, message)
            VALUES (?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $_POST['name'],
            $_POST['email'],
            $_POST['subject'],
            $_POST['message']
        ]);
        
        $message = '<div class="success-message">Message sent successfully!</div>';
    } catch (PDOException $e) {
        $message = '<div class="error-message">Failed to send message. Please try again.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Disaster Management System</title>
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
                <li><a href="reports.php">Reports</a></li>
                <li><a href="contact.php" class="active">Contact</a></li>
                <li><a href="admin_login.php">Admin</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Contact Us</h1>
            <p>Get in touch with our disaster management team for assistance or information</p>
        </div>
    </section>

    <section class="content">
        <h1>Contact Us</h1>
        
        <div class="contact-info">
            <div class="info-grid">
                <div class="info-card">
                    <h3><i class="icon">📞</i> Emergency Hotline</h3>
                    <p>24/7 Support: <strong>1-800-DISASTER</strong></p>
                </div>
                <div class="info-card">
                    <h3><i class="icon">📧</i> Email</h3>
                    <p>support@disastermanagement.org</p>
                </div>
                <div class="info-card">
                    <h3><i class="icon">📍</i> Headquarters</h3>
                    <p>123 Emergency Ave, Crisis City, 10001</p>
                </div>
            </div>
        </div>
        
        <div class="contact-form">
            <h2>Send Us a Message</h2>

            <!-- Add this before the form -->
            <?php if ($message): ?>
                <?php echo $message; ?>
            <?php endif; ?>

            <!-- Update the form action to submit to itself -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Your name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Your email address" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" placeholder="Message subject" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" placeholder="Your message" required></textarea>
                </div>
                
                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Disaster Management System. <a href="#">Privacy Policy</a></p>
    </footer>
</body>
</html>