<?php
include 'db_connect.php';

// Get database structure information
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

// Get record counts for each table
$table_counts = [];
foreach ($tables as $table) {
    $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
    $table_counts[$table] = $count;
}

// Get sample data from each table
$sample_data = [];
foreach ($tables as $table) {
    $sample_data[$table] = $pdo->query("SELECT * FROM $table LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Overview - Disaster Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .database-overview {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .table-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s;
        }
        
        .table-card:hover {
            transform: translateY(-5px);
        }
        
        .table-card h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .record-count {
            background-color: #3498db;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
        }
        
        .table-structure {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .table-structure h4 {
            margin-bottom: 10px;
            color: #34495e;
        }
        
        .structure-item {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .structure-item:last-child {
            border-bottom: none;
        }
        
        .field-name {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .field-type {
            color: #e74c3c;
            font-size: 0.9rem;
        }
        
        .sample-data {
            margin-top: 15px;
        }
        
        .sample-data h4 {
            margin-bottom: 10px;
            color: #34495e;
        }
        
        .sample-item {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }
        
        .sample-item:last-child {
            margin-bottom: 0;
        }
        
        .view-all {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #3498db;
            text-decoration: none;
        }
        
        .view-all:hover {
            text-decoration: underline;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .modal-content {
            position: relative;
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            width: 90%;
            max-width: 1000px;
            max-height: 80vh;
            overflow-y: auto;
            border-radius: 8px;
        }

        .close-modal {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 24px;
            cursor: pointer;
        }

        .modal-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .modal-table th,
        .modal-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .modal-table th {
            background-color: #2c3e50;
            color: white;
        }

        /* Query section styles */
        .query-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 30px;
        }

        .query-section h2 {
            margin-bottom: 15px;
            color: #34495e;
        }

        .query-form {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }

        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: monospace;
            font-size: 14px;
            resize: vertical;
        }

        .submit-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #2980b9;
        }

        .query-results {
            margin-top: 20px;
            overflow-x: auto;
        }

        .query-results h3 {
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .alert {
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }

        .alert-error {
            background-color: #ffe6e6;
            color: #cc0000;
            border: 1px solid #ffcccc;
        }

        .alert-info {
            background-color: #e6f3ff;
            color: #004d99;
            border: 1px solid #cce6ff;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Admin Dashboard</div>
            <ul>
                <li><a href="admin_dashboard.php">Events</a></li>
                <li><a href="admin_messages.php">Messages</a></li>
                <li><a href="overview.php" class="active">DB Overview</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="database-overview">
        <h1>Database Overview</h1>
        
        <div class="tables-grid">
            <?php foreach ($tables as $table): ?>
            <div class="table-card">
                <h3>
                    <?php echo htmlspecialchars($table); ?>
                    <span class="record-count"><?php echo $table_counts[$table]; ?> records</span>
                </h3>
                
                <div class="table-structure">
                    <h4>Structure</h4>
                    <?php
                    // Get table structure
                    $columns = $pdo->query("DESCRIBE $table")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($columns as $column): ?>
                    <div class="structure-item">
                        <div class="field-name"><?php echo htmlspecialchars($column['Field']); ?></div>
                        <div class="field-type"><?php echo htmlspecialchars($column['Type']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="sample-data">
                    <h4>Sample Data (<?php echo min(5, $table_counts[$table]); ?> records)</h4>
                    <?php if ($table_counts[$table] > 0): ?>
                        <?php foreach ($sample_data[$table] as $index => $row): ?>
                        <div class="sample-item">
                            <?php 
                            $values = array_slice($row, 0, 3); // Show first 3 values
                            foreach ($values as $key => $value): 
                                if ($value === null) continue;
                            ?>
                                <div><strong><?php echo htmlspecialchars($key); ?>:</strong> 
                                <?php echo htmlspecialchars(substr(strval($value), 0, 30)); ?>
                                <?php if (strlen(strval($value)) > 30) echo '...'; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if ($table_counts[$table] > 5): ?>
                        <a href="#" class="view-all" data-table="<?php echo htmlspecialchars($table); ?>">
                            View all <?php echo $table_counts[$table]; ?> records
                        </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="sample-item">No data available</div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Add this new section for SQL queries -->
        <div class="query-section">
            <h2>Run SQL Query</h2>
            <form method="post" class="query-form">
                <div class="form-group">
                    <label for="sql_query">Enter your SELECT query:</label>
                    <textarea id="sql_query" name="sql_query" rows="4" required
                        placeholder="Example queries:
SELECT * FROM Personnel LIMIT 5;
SELECT type, location, severity FROM Disaster_Events;
SELECT name, status FROM Shelters;"></textarea>
                </div>
                <button type="submit" name="run_query" class="submit-btn">Run Query</button>
            </form>

            <?php
            // Handle query execution
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_query'])) {
                try {
                    // Clean and validate the query
                    $sql_query = trim($_POST['sql_query']);
                    
                    // Basic SQL injection prevention
                    $blocked_words = array("DROP", "DELETE", "UPDATE", "INSERT", "ALTER", "CREATE", "TRUNCATE");
                    foreach ($blocked_words as $word) {
                        if (stripos($sql_query, $word) !== false) {
                            throw new Exception('Only SELECT queries are allowed');
                        }
                    }

                    // Ensure query starts with SELECT
                    if (!preg_match('/^\s*SELECT\s+/i', $sql_query)) {
                        throw new Exception('Query must start with SELECT');
                    }

                    // Remove any trailing semicolons and extra whitespace
                    $sql_query = rtrim(rtrim($sql_query), ';');

                    // Execute the query
                    try {
                        $query = $pdo->query($sql_query);
                        if ($query === false) {
                            throw new Exception('Query execution failed');
                        }
                        
                        $results = $query->fetchAll(PDO::FETCH_ASSOC);

                        if (count($results) > 0) {
                            echo '<div class="query-results">';
                            echo '<h3>Query Results</h3>';
                            echo '<table class="modal-table">';
                            
                            // Table headers
                            echo '<thead><tr>';
                            foreach (array_keys($results[0]) as $header) {
                                echo '<th>' . htmlspecialchars($header) . '</th>';
                            }
                            echo '</tr></thead>';
                            
                            // Table body
                            echo '<tbody>';
                            foreach ($results as $row) {
                                echo '<tr>';
                                foreach ($row as $value) {
                                    echo '<td>' . htmlspecialchars($value ?? 'NULL') . '</td>';
                                }
                                echo '</tr>';
                            }
                            echo '</tbody>';
                            echo '</table>';
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-info">Query executed successfully but no results found</div>';
                        }
                    } catch (PDOException $e) {
                        throw new Exception('Query execution failed: ' . $e->getMessage());
                    }
                } catch (Exception $e) {
                    echo '<div class="alert alert-error">' . htmlspecialchars($e->getMessage()) . '</div>';
                }
            }
            ?>
        </div>
    </section>

    <div id="recordModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2 id="modalTitle"></h2>
            <div id="modalContent"></div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('recordModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        const closeBtn = document.querySelector('.close-modal');

        // Close modal when clicking the close button or outside the modal
        closeBtn.onclick = () => modal.style.display = "none";
        window.onclick = (e) => {
            if (e.target == modal) modal.style.display = "none";
        }

        // Add click handlers to all "View all" links
        document.querySelectorAll('.view-all').forEach(link => {
            link.onclick = async (e) => {
                e.preventDefault();
                const table = link.getAttribute('data-table');
                modalTitle.textContent = `All Records - ${table}`;
                modal.style.display = "block";
                
                try {
                    const response = await fetch(`get_records.php?table=${table}`);
                    const data = await response.json();
                    
                    if (data.length > 0) {
                        // Create table headers
                        let html = '<table class="modal-table"><thead><tr>';
                        Object.keys(data[0]).forEach(key => {
                            html += `<th>${key}</th>`;
                        });
                        html += '</tr></thead><tbody>';
                        
                        // Add table rows
                        data.forEach(row => {
                            html += '<tr>';
                            Object.values(row).forEach(value => {
                                html += `<td>${value ?? ''}</td>`;
                            });
                            html += '</tr>';
                        });
                        
                        html += '</tbody></table>';
                        modalContent.innerHTML = html;
                    } else {
                        modalContent.innerHTML = '<p>No records found</p>';
                    }
                } catch (error) {
                    modalContent.innerHTML = '<p>Error loading records</p>';
                }
            };
        });
    });
    </script>

    <footer>
        <p>&copy; 2025 Disaster Management System. <a href="#">Privacy Policy</a></p>
    </footer>
</body>
</html>