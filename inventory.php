<?php
require 'config.php';

// Handle AJAX requests
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    switch ($_GET['action']) {
        case 'delete':
            if (isset($_POST['id'])) {
                $id = intval($_POST['id']);
                $stmt = $conn->prepare("DELETE FROM items WHERE item_id = ?");
                $stmt->bind_param("i", $id);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Item deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error deleting item']);
                }
                $stmt->close();
            }
            break;
            
        case 'add':
            if (isset($_POST['stock_number'])) {
                $stock_number = $_POST['stock_number'];
                $description = $_POST['description'];
                $unit = $_POST['unit'];
                $reorder_point = intval($_POST['reorder_point']);
                $unit_cost = floatval($_POST['unit_cost']);
                $quantity_on_hand = intval($_POST['quantity_on_hand']);
                
                $stmt = $conn->prepare("INSERT INTO items (stock_number, description, unit, reorder_point, unit_cost, quantity_on_hand) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssiid", $stock_number, $description, $unit, $reorder_point, $unit_cost, $quantity_on_hand);
                
                if ($stmt->execute()) {
                    $new_id = $conn->insert_id;
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Item added successfully',
                        'item' => [
                            'item_id' => $new_id,
                            'stock_number' => $stock_number,
                            'description' => $description,
                            'unit' => $unit,
                            'reorder_point' => $reorder_point,
                            'unit_cost' => $unit_cost,
                            'quantity_on_hand' => $quantity_on_hand,
                            'created_at' => date('Y-m-d H:i:s')
                        ]
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error adding item']);
                }
                $stmt->close();
            }
            break;
            
        case 'update':
            if (isset($_POST['item_id'])) {
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
                
                if ($stmt->execute()) {
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Item updated successfully',
                        'item' => [
                            'item_id' => $id,
                            'stock_number' => $stock_number,
                            'description' => $description,
                            'unit' => $unit,
                            'reorder_point' => $reorder_point,
                            'unit_cost' => $unit_cost,
                            'quantity_on_hand' => $quantity_on_hand
                        ]
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error updating item']);
                }
                $stmt->close();
            }
            break;
    }
    
    $conn->close();
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
        <table id="inventoryTable">
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
                        
                        echo "<tr data-id='{$row['item_id']}'>
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

                                <button 
                                    class='btn delete-btn' 
                                    onclick='deleteItem({$row['item_id']})' 
                                    title='Delete Item'
                                >
                                    <i class='fas fa-trash'></i> Delete
                                </button>
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
        <form id="addForm">
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
        <form id="editForm">
            <input type="hidden" name="item_id" id="edit_item_id">
            <input type="text" name="stock_number" id="edit_stock_number" placeholder="Stock Number" required>
            <input type="text" name="description" id="edit_description" placeholder="Description" required>
            <input type="text" name="unit" id="edit_unit" placeholder="Unit" required>
            <input type="number" name="reorder_point" id="edit_reorder_point" placeholder="Reorder Point" required min="0">
            <input type="number" step="0.01" name="unit_cost" id="edit_unit_cost" placeholder="Unit Cost (₱)" required min="0">
            <input type="number" name="quantity_on_hand" id="edit_quantity_on_hand" placeholder="Quantity on Hand" required min="0">
            <button type="submit" class="save-btn">
                <i class="fas fa-save"></i> Update Item
            </button>
        </form>
    </div>
</div>

<!-- Notification -->
<div id="notification" class="notification"></div>

<script src="js/inventory_script.js?v=<?= time() ?>"></script>

</body>
</html>