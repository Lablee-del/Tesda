<?php
$servername = "localhost";
$username = "root"; // default for XAMPP
$password = "";     // default for XAMPP (no password)
$dbname = "tesda_inventory";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// If connected successfully
// echo "Connected successfully";
?>
