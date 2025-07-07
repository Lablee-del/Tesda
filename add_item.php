<?php
require 'config.php'; // connects to the database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stock_number = $_POST['stock_number'];
    $description = $_POST['description'];
    $unit = $_POST['unit'];
    $reorder_point = $_POST['reorder_point'];
    $unit_cost = $_POST['unit_cost'];
    $quantity_on_hand = $_POST['quantity_on_hand'];

    // Insert into database
    $sql = "INSERT INTO items (stock_number, description, unit, reorder_point, unit_cost, quantity_on_hand)
            VALUES ('$stock_number', '$description', '$unit', $reorder_point, $unit_cost, $quantity_on_hand)";

    if ($conn->query($sql) === TRUE) {
        header("Location: inventory.php"); // After saving, return to inventory page
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
