<?php
/* ----------  DATABASE CONNECTION  ---------- */

$host     = "localhost";   // XAMPP runs on localhost
$user     = "root";        // default MySQL user in XAMPP
$password = "";            // leave empty unless you set one
$database = "disaster_management";   // make sure this DB exists

$conn = new mysqli($host, $user, $password, $database);

/*  Stop the script immediately if connection fails  */
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/*  If you want a silent connection (no output) just delete the echo line  */
//echo "Connected to database.";
?>
