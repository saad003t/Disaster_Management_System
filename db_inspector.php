<?php
include 'db_connect.php';

// Get all tables in the database
$tables = [];
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_row()) {
    $tables[] = $row[0];
}

// Handle table selection and display
$selectedTable = $_GET['table'] ?? null;
$tableData = [];
$columns = [];
$errorMsg = "";

if ($selectedTable && in_array($selectedTable, $tables)) {
    $result = $conn->query("SELECT * FROM `$selectedTable`");
    if ($result) {
        $tableData = $result->fetch_all(MYSQLI_ASSOC);
        if (!empty($tableData)) {
            $columns = array_keys($tableData[0]);
        }
    } else {
        $errorMsg = $conn->error;
    }
}

// Handle SELECT queries
$querySQL = "";
$resultRows = [];
$resultFields = [];

if (!empty($_POST['sql'])) {
    $querySQL = trim($_POST['sql']);

    // Only allow SELECT statements for safety
    if (preg_match('/^\s*select/i', $querySQL)) {
        $queryRes = $conn->query($querySQL);
        if ($queryRes) {
            $resultFields = $queryRes->fetch_fields();
            while ($row = $queryRes->fetch_assoc()) {
                $resultRows[] = $row;
            }
        } else {
            $errorMsg = $conn->error;
        }
    } else {
        $errorMsg = "Only SELECT statements are allowed in this console.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DB Inspector • Disaster Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        h1 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        h2 {
            font-size: 20px;
            margin: 20px 0 15px;
            color: #34495e;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        
        h3 {
            font-size: 16px;
            margin: 15px 0 10px;
            color: #7f8c8d;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .table-list {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .table-list ul {
            list-style-type: none;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }
        
        .table-list li {
            margin: 5px 0;
        }
        
        .table-list a {
            display: block;
            padding: 8px 12px;
            color: #3498db;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .table-list a:hover {
            background-color: #f0f7ff;
            text-decoration: underline;
        }
        
        .table-list a.active {
            background-color: #3498db;
            color: white;
        }
        
        .table-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        th, td {
            padding: 10px 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tr:hover {
            background-color: #f0f0f0;
        }
        
        .query-form {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: monospace;
            margin-bottom: 10px;
            min-height: 100px;
            resize: vertical;
        }
        
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.2s;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .error {
            color: #e74c3c;
            margin: 10px 0;
            padding: 10px;
            background: #fdecea;
            border-radius: 4px;
        }
        
        .no-data {
            color: #7f8c8d;
            font-style: italic;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Database Inspector</h1>
    </div>
    
    <a href="index.php" class="back-link">← Back to Disaster Events</a>
    
    <div class="table-list">
        <h2>Disaster Management Tables</h2>
        <ul>
            <?php foreach ($tables as $table): ?>
                <li>
                    <a href="?table=<?= $table ?>" class="<?= $selectedTable === $table ? 'active' : '' ?>">
                        <?= $table ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <?php if ($selectedTable): ?>
        <div class="table-container">
            <h2>Table: <?= $selectedTable ?></h2>
            
            <?php if (!empty($errorMsg)): ?>
                <div class="error">❌ <?= $errorMsg ?></div>
            <?php elseif (!empty($tableData)): ?>
                <table>
                    <thead>
                        <tr>
                            <?php foreach ($columns as $column): ?>
                                <th><?= $column ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tableData as $row): ?>
                            <tr>
                                <?php foreach ($row as $cell): ?>
                                    <td><?= htmlspecialchars($cell) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">No data available in this table</div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="query-form">
        <h2>Run a SELECT Query</h2>
        <form method="POST">
            <textarea name="sql" placeholder="e.g. SELECT * FROM Disaster_Events LIMIT 5"><?= htmlspecialchars($querySQL) ?></textarea>
            <button type="submit">Run Query</button>
        </form>
        
        <?php if (!empty($errorMsg) && empty($selectedTable)): ?>
            <div class="error">❌ <?= $errorMsg ?></div>
        <?php elseif (!empty($resultRows)): ?>
            <h3>Query Results (<?= count($resultRows) ?> rows)</h3>
            <table>
                <thead>
                    <tr>
                        <?php foreach ($resultFields as $field): ?>
                            <th><?= $field->name ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultRows as $row): ?>
                        <tr>
                            <?php foreach ($row as $cell): ?>
                                <td><?= htmlspecialchars($cell) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (!empty($querySQL)): ?>
            <div class="no-data">No rows returned</div>
        <?php endif; ?>
    </div>
    
    <a href="index.php" class="back-link">← Back to Disaster Events</a>
</body>
</html>