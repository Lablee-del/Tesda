<?php
require 'config.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM items WHERE item_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: inventory.php");
    exit();
}

// Handle Update
if (isset($_POST['update_item'])) {
    $id = intval($_POST['item_id']);
    $stock_number = $_POST['stock_number'];
    $description = $_POST['description'];
    $unit = $_POST['unit'];
    $reorder_point = intval($_POST['reorder_point']);
    $unit_cost = floatval($_POST['unit_cost']);
    $quantity_on_hand = intval($_POST['quantity_on_hand']);

    $stmt = $conn->prepare("UPDATE items SET 
                stock_number = ?,
                description = ?,
                unit = ?,
                reorder_point = ?,
                unit_cost = ?,
                quantity_on_hand = ?
            WHERE item_id = ?");
    
    $stmt->bind_param("sssiidi", $stock_number, $description, $unit, $reorder_point, $unit_cost, $quantity_on_hand, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: inventory.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TESDA Inventory Management System</title>
    <link rel="stylesheet" href="css/styles.css?v=<?= time() ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="container">
    <h2><i class=""></i> TESDA Inventory</h2>

    <button class="add-btn" onclick="document.getElementById('addModal').style.display='block'">
        <i class="fas fa-plus"></i> Add New Item
    </button>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th><i class=""></i> Stock Number</th>
                    <th><i class=""></i> Description</th>
                    <th><i class=""></i> Unit</th>
                    <th><i class=""></i> Quantity</th>
                    <th><i class=""></i> Unit Cost</th>
                    <th><i class=""></i> Total Cost</th>
                    <th><i class=""></i> Reorder Point</th>
                    <th><i class=""></i> Last Updated</th>
                    <th><i class=""></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM items ORDER BY stock_number ASC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $total_cost = $row["quantity_on_hand"] * $row["unit_cost"];
                        $status_class = $row["quantity_on_hand"] <= $row["reorder_point"] ? 'status-low' : 'status-normal';
                        
                        echo "<tr>
                            <td><strong>{$row['stock_number']}</strong></td>
                            <td>{$row['description']}</td>
                            <td>{$row['unit']}</td>
                            <td>
                                <span class='$status_class'>
                                    {$row['quantity_on_hand']}
                                </span>
                            </td>
                            <td class='currency'>₱ " . number_format($row['unit_cost'], 2) . "</td>
                            <td class='currency'>₱ " . number_format($total_cost, 2) . "</td>
                            <td>{$row['reorder_point']}</td>
                            <td>" . date('M d, Y H:i', strtotime($row['created_at'] ?? 'now')) . "</td>
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
                                    title='Edit Item'
                                >
                                    <i class='fas fa-edit'></i> Edit
                                </button>

                                <a href='inventory.php?delete={$row['item_id']}' 
                                   class='btn delete-btn' 
                                   onclick='return confirm(\"Are you sure you want to delete this item?\")' 
                                   title='Delete Item'>
                                    <i class='fas fa-trash'></i> Delete
                                </a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' style='text-align: center; color: #666; font-style: italic;'>
                            <i class='fas fa-inbox'></i> No inventory data found.
                          </td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Item Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('addModal').style.display='none'">&times;</span>
        <h3><i class="fas fa-plus-circle"></i> Add New Item</h3>
        <form method="post" action="add_item.php">
            <input type="text" name="stock_number" placeholder="Stock Number" required>
            <input type="text" name="description" placeholder="Description" required>
            <input type="text" name="unit" placeholder="Unit (pcs, box, etc.)" required>
            <input type="number" name="reorder_point" placeholder="Reorder Point" required min="0">
            <input type="number" step="0.01" name="unit_cost" placeholder="Unit Cost (₱)" required min="0">
            <input type="number" name="quantity_on_hand" placeholder="Quantity on Hand" required min="0">
            <button type="submit" class="save-btn">
                <i class="fas fa-save"></i> Save Item
            </button>
        </form>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
        <h3><i class="fas fa-edit"></i> Edit Item</h3>
        <form method="post">
            <input type="hidden" name="item_id" id="edit_item_id">
            <input type="text" name="stock_number" id="edit_stock_number" placeholder="Stock Number" required>
            <input type="text" name="description" id="edit_description" placeholder="Description" required>
            <input type="text" name="unit" id="edit_unit" placeholder="Unit" required>
            <input type="number" name="reorder_point" id="edit_reorder_point" placeholder="Reorder Point" required min="0">
            <input type="number" step="0.01" name="unit_cost" id="edit_unit_cost" placeholder="Unit Cost (₱)" required min="0">
            <input type="number" name="quantity_on_hand" id="edit_quantity_on_hand" placeholder="Quantity on Hand" required min="0">
            <button type="submit" name="update_item" class="save-btn">
                <i class="fas fa-save"></i> Update Item
            </button>
        </form>
    </div>
</div>

<script>
function openEditModal(button) {
    const modal = document.getElementById('editModal');
    
    // Populate form fields
    document.getElementById('edit_item_id').value = button.getAttribute('data-id');
    document.getElementById('edit_stock_number').value = button.getAttribute('data-stock_number');
    document.getElementById('edit_description').value = button.getAttribute('data-description');
    document.getElementById('edit_unit').value = button.getAttribute('data-unit');
    document.getElementById('edit_reorder_point').value = button.getAttribute('data-reorder_point');
    document.getElementById('edit_unit_cost').value = button.getAttribute('data-unit_cost');
    document.getElementById('edit_quantity_on_hand').value = button.getAttribute('data-quantity_on_hand');

    modal.style.display = 'block';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    
    if (event.target === addModal) { 
        addModal.style.display = "none"; 
    }
    if (event.target === editModal) { 
        editModal.style.display = "none"; 
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.getElementById('addModal').style.display = 'none';
        document.getElementById('editModal').style.display = 'none';
    }
});

// Add loading state to buttons
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<div class="loading"></div>' + originalText;
        submitBtn.disabled = true;
    });
});
</script>

</body>
</html>