<?php
require 'config.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM items WHERE item_id = $id");
    header("Location: inventory.php");
    exit();
}

// Handle Update
if (isset($_POST['update_item'])) {
    $id = $_POST['item_id'];
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

    $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TESDA Inventory List</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 20px; }
        .container { max-width: 1200px; margin: auto; }
        h2 { color: #333; }
        .add-btn { background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background-color: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.05); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .btn { padding: 5px 10px; margin-right: 5px; border: none; border-radius: 3px; cursor: pointer; }
        .edit-btn { background-color: #2196F3; color: white; }
        .delete-btn { background-color: #f44336; color: white; }
        .modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.4); }
        .modal-content { background-color: #fff; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 400px; border-radius: 8px; }
        .modal input[type=text], .modal input[type=number] { width: 100%; padding: 10px; margin: 8px 0; box-sizing: border-box; }
        .modal .save-btn { background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="container">

<h2>TESDA Inventory List (Supplies)</h2>

<button class="add-btn" onclick="document.getElementById('addModal').style.display='block'">Add New Item</button>

<table>
    <tr>
        <th>Stock Number</th>
        <th>Description</th>
        <th>Unit</th>
        <th>Quantity on Hand</th>
        <th>Unit Cost</th>
        <th>Total Cost</th>
        <th>Reorder Point</th>
        <th>Last Updated</th>
        <th>Actions</th>
    </tr>

    <?php
    require 'config.php';

    $sql = "SELECT * FROM items";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $total_cost = $row["quantity_on_hand"] * $row["unit_cost"];
            echo "<tr>
                <td>{$row['stock_number']}</td>
                <td>{$row['description']}</td>
                <td>{$row['unit']}</td>
                <td>{$row['quantity_on_hand']}</td>
                <td>₱ " . number_format($row['unit_cost'], 2) . "</td>
                <td>₱ " . number_format($total_cost, 2) . "</td>
                <td>{$row['reorder_point']}</td>
                <td>" . date('Y-m-d H:i:s') . "</td>
                <td>
                    <button 
                        class='btn edit-btn' 
                        onclick='openEditModal(this)'
                        data-id='{$row['item_id']}'
                        data-stock_number='{$row['stock_number']}'
                        data-description='{$row['description']}'
                        data-unit='{$row['unit']}'
                        data-reorder_point='{$row['reorder_point']}'
                        data-unit_cost='{$row['unit_cost']}'
                        data-quantity_on_hand='{$row['quantity_on_hand']}'
                    >Edit</button>

                    <a href='inventory.php?delete={$row['item_id']}' class='btn delete-btn' onclick='return confirm('Are you sure?')>Delete</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No inventory data found.</td></tr>";
    }

    $conn->close();
    ?>
</table>

<!-- Add Item Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('addModal').style.display='none'">&times;</span>
        <h3>Add New Item</h3>
        <form method="post" action="add_item.php">
            <input type="text" name="stock_number" placeholder="Stock Number" required>
            <input type="text" name="description" placeholder="Description" required>
            <input type="text" name="unit" placeholder="Unit (pcs, box, etc.)" required>
            <input type="number" name="reorder_point" placeholder="Reorder Point" required>
            <input type="number" step="0.01" name="unit_cost" placeholder="Unit Cost" required>
            <input type="number" name="quantity_on_hand" placeholder="Quantity on Hand" required>
            <input type="submit" class="save-btn" value="Save Item">
        </form>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
        <h3>Edit Item</h3>
        <form method="post">
            <input type="hidden" name="item_id" id="edit_item_id">
            <input type="text" name="stock_number" id="edit_stock_number" required>
            <input type="text" name="description" id="edit_description" required>
            <input type="text" name="unit" id="edit_unit" required>
            <input type="number" name="reorder_point" id="edit_reorder_point" required>
            <input type="number" step="0.01" name="unit_cost" id="edit_unit_cost" required>
            <input type="number" name="quantity_on_hand" id="edit_quantity_on_hand" required>
            <input type="submit" name="update_item" class="save-btn" value="Update Item">
        </form>
    </div>
</div>

</div>

<script>
function openEditModal(button) {
    document.getElementById('edit_item_id').value = button.getAttribute('data-id');
    document.getElementById('edit_stock_number').value = button.getAttribute('data-stock_number');
    document.getElementById('edit_description').value = button.getAttribute('data-description');
    document.getElementById('edit_unit').value = button.getAttribute('data-unit');
    document.getElementById('edit_reorder_point').value = button.getAttribute('data-reorder_point');
    document.getElementById('edit_unit_cost').value = button.getAttribute('data-unit_cost');
    document.getElementById('edit_quantity_on_hand').value = button.getAttribute('data-quantity_on_hand');

    document.getElementById('editModal').style.display = 'block';
}

window.onclick = function(event) {
    var addModal = document.getElementById('addModal');
    var editModal = document.getElementById('editModal');
    if (event.target === addModal) { addModal.style.display = "none"; }
    if (event.target === editModal) { editModal.style.display = "none"; }
}
</script>

</body>
</html>
