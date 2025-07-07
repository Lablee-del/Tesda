<?php
require 'config.php';

// Get the item details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM items WHERE item_id = $id");
    $item = $result->fetch_assoc();
}

// Update the item
if (isset($_POST['update_item'])) {
    $stock_number = $_POST['stock_number'];
    $description = $_POST['description'];
    $unit = $_POST['unit'];
    $reorder_point = $_POST['reorder_point'];
    $unit_cost = $_POST['unit_cost'];
    $quantity_on_hand = $_POST['quantity_on_hand'];

    $sql = "UPDATE items SET 
                stock_number = '$stock_number',
                description = '$description',
                unit = '$unit',
                reorder_point = $reorder_point,
                unit_cost = $unit_cost,
                quantity_on_hand = $quantity_on_hand
            WHERE item_id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: inventory.php");
        exit();
    } else {
        echo "Error updating item: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Item</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        input[type=text], input[type=number] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            box-sizing: border-box;
        }
        input[type=submit] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>Edit Item</h2>

<form method="post">
    <input type="text" name="stock_number" value="<?= $item['stock_number'] ?>" required>
    <input type="text" name="description" value="<?= $item['description'] ?>" required>
    <input type="text" name="unit" value="<?= $item['unit'] ?>" required>
    <input type="number" name="reorder_point" value="<?= $item['reorder_point'] ?>" required>
    <input type="number" step="0.01" name="unit_cost" value="<?= $item['unit_cost'] ?>" required>
    <input type="number" name="quantity_on_hand" value="<?= $item['quantity_on_hand'] ?>" required>
    <input type="submit" name="update_item" value="Update Item">
</form>

</body>
</html>
