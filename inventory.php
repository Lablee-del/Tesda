<?php
require 'config.php';
require 'functions.php';
// Handle AJAX requests
if (isset($_GET['action'])) {
    header('Content-Type: application/json');


// save history 
    function logItemHistory($conn, $item_id, $change_type = 'update') {
    // Fetch current item info
    $stmt = $conn->prepare("SELECT * FROM items WHERE item_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();

    // Get previous quantity (from latest history or initial_quantity fallback)
    $prev_stmt = $conn->prepare("SELECT quantity_on_hand FROM item_history WHERE item_id = ? ORDER BY changed_at DESC LIMIT 1");
    $prev_stmt->bind_param("i", $item_id);
    $prev_stmt->execute();
    $prev_result = $prev_stmt->get_result();
    $prev_row = $prev_result->fetch_assoc();
    $prev_stmt->close();

    $previous_quantity = $prev_row ? intval($prev_row['quantity_on_hand']) : intval($item['initial_quantity']);
    $current_quantity = intval($item['quantity_on_hand']);
    $quantity_change = $current_quantity - $previous_quantity;

    $change_direction = match(true) {
        $quantity_change > 0 => 'increase',
        $quantity_change < 0 => 'decrease',
        default              => 'no_change'
    };

    // Insert into history
$insert = $conn->prepare("
    INSERT INTO item_history (
        item_id,
        stock_number,
        item_name,
        description,
        unit,
        reorder_point,
        unit_cost,
        quantity_on_hand,
        quantity_change,
        change_direction,
        change_type
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$insert->bind_param(
    "issssdiisss",
    $item_id,
    $item['stock_number'],
    $item['item_name'],
    $item['description'],
    $item['unit'],
    $item['reorder_point'],
    $item['unit_cost'],
    $current_quantity,
    $quantity_change,
    $change_direction,
    $change_type
);

$insert->execute();
$insert->close();
}
    
    switch ($_GET['action']) {
        case 'check_stock':
            if (isset($_POST['stock_number'])) {
                $stock_number = $_POST['stock_number'];
                $stmt = $conn->prepare("SELECT * FROM items WHERE stock_number = ? LIMIT 1");
                $stmt->bind_param("s", $stock_number);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo json_encode([
                        'exists' => true,
                        'item' => [
                            'item_name' => $row['item_name'],
                            'description' => $row['description'],
                            'unit' => $row['unit'],
                            'reorder_point' => $row['reorder_point'],
                            'unit_cost' => $row['unit_cost']
                        ]
                    ]);
                } else {
                    echo json_encode(['exists' => false]);
                }
                $stmt->close();
            }
            break;
            
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
                $item_name = $_POST['item_name'];
                $description = $_POST['description'];
                $unit = $_POST['unit'];
                $reorder_point = intval($_POST['reorder_point']);
                $unit_cost = floatval($_POST['unit_cost']);
                $quantity_on_hand = intval($_POST['quantity_on_hand']);
                
                // Check if stock number already exists
                $check_stmt = $conn->prepare("SELECT item_id FROM items WHERE stock_number = ?");
                $check_stmt->bind_param("s", $stock_number);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                
                if ($check_result->num_rows > 0) {
                    // Add new inventory entry for existing item
                    $existing_item = $check_result->fetch_assoc();
                    $entry_stmt = $conn->prepare("INSERT INTO inventory_entries (item_id, quantity, unit_cost) VALUES (?, ?, ?)");
                    $entry_stmt->bind_param("iid", $existing_item['item_id'], $quantity_on_hand, $unit_cost);
                    
                    // Check if this is the first entry for this item
                    $count_stmt = $conn->prepare("SELECT COUNT(*) as entry_count FROM inventory_entries WHERE item_id = ?");
                    $count_stmt->bind_param("i", $existing_item['item_id']);
                    $count_stmt->execute();
                    $count_result = $count_stmt->get_result();
                    $count_row = $count_result->fetch_assoc();

                    $count_stmt->close();
                    if ($entry_stmt->execute()) {
                        // Update the main item's quantity_on_hand by adding the new entry quantity
                        $update_qty_stmt = $conn->prepare("UPDATE items SET quantity_on_hand = quantity_on_hand + ? WHERE item_id = ?");
                        $update_qty_stmt->bind_param("ii", $quantity_on_hand, $existing_item['item_id']);
                        $update_qty_stmt->execute();
                        $update_qty_stmt->close();

                        updateAverageCost($conn, $existing_item['item_id']);
                        //Log History
                        logItemHistory($conn, $existing_item['item_id'], 'entry');

                        echo json_encode([
                            'success' => true, 
                            'message' => 'New inventory entry added',
                            'updated' => true,
                            'item_id' => $existing_item['item_id'],
                            'has_multiple_entries' => true  // ADD THIS LINE
                        ]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error adding inventory entry']);
                    }
                    $entry_stmt->close();
                } else {
                    // Insert new item
                    $stmt = $conn->prepare("INSERT INTO items (stock_number, item_name, description, unit, reorder_point, unit_cost, quantity_on_hand, initial_quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssiidi", $stock_number, $item_name, $description, $unit, $reorder_point, $unit_cost, $quantity_on_hand, $quantity_on_hand);
                    
                    if ($stmt->execute()) {
                        $new_id = $conn->insert_id;

                        updateAverageCost($conn, $new_id);

                        echo json_encode([
                            'success' => true, 
                            'message' => 'Item added successfully',
                            'item' => [
                            'item_id' => $new_id,
                            'stock_number' => $stock_number,
                            'item_name' => $item_name,
                            'description' => $description,
                            'unit' => $unit,
                            'reorder_point' => $reorder_point,
                            'unit_cost' => $unit_cost,
                            'quantity_on_hand' => $quantity_on_hand,
                            'created_at' => date('Y-m-d H:i:s'),
                            'has_multiple_entries' => false  
                        ]
                        ]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error adding item']);
                    }
                    $stmt->close();
                }
                $check_stmt->close();
            }
            break;
            
        case 'update':
            if (isset($_POST['item_id'])) {
                $id = intval($_POST['item_id']);
                
                // Check if item has multiple entries before allowing unit_cost and quantity updates
                $check_entries_stmt = $conn->prepare("
                    SELECT 
                        (SELECT COUNT(*) FROM inventory_entries WHERE item_id = ?) as entry_count,
                        initial_quantity
                    FROM items WHERE item_id = ?
                ");
                $check_entries_stmt->bind_param("ii", $id, $id);
                $check_entries_stmt->execute();
                $check_result = $check_entries_stmt->get_result();
                $check_row = $check_result->fetch_assoc();
                
                $has_multiple_entries = ($check_row['initial_quantity'] > 0 && $check_row['entry_count'] > 0);
                $check_entries_stmt->close();
                
                $stock_number = $_POST['stock_number'];
                $item_name = $_POST['item_name'];
                $description = $_POST['description'];
                $unit = $_POST['unit'];
                $reorder_point = intval($_POST['reorder_point']);
                
                if ($has_multiple_entries) {
                    // Only update basic fields, not unit_cost and quantity_on_hand
                    $stmt = $conn->prepare("UPDATE items SET 
                                stock_number = ?,
                                item_name = ?,
                                description = ?,
                                unit = ?,
                                reorder_point = ?
                            WHERE item_id = ?");
                    $stmt->bind_param("ssssii", $stock_number, $item_name, $description, $unit, $reorder_point, $id);
                    
                    if ($stmt->execute()) {

                    // Check if this item doesn't have an initial_quantity set yet
                    $check_initial_stmt = $conn->prepare("SELECT initial_quantity FROM items WHERE item_id = ?");
                    $check_initial_stmt->bind_param("i", $id);
                    $check_initial_stmt->execute();
                    $check_result = $check_initial_stmt->get_result();
                    $check_row = $check_result->fetch_assoc();
                    
                    // If initial_quantity is 0 or null, set it to the quantity_on_hand being saved
                    if (empty($check_row['initial_quantity'])) {
                        $set_initial_stmt = $conn->prepare("UPDATE items SET initial_quantity = ? WHERE item_id = ?");
                        $set_initial_stmt->bind_param("ii", $quantity_on_hand, $id);
                        $set_initial_stmt->execute();
                        $set_initial_stmt->close();
                    }
                    $check_initial_stmt->close();
                    
                    // UPDATE AVERAGE COST - ADD THIS LINE:
                    updateAverageCost($conn, $id);
                    //Log history
                    logItemHistory($conn, $id);
                    
                    // Get current values for response
                    $get_stmt = $conn->prepare("SELECT * FROM items WHERE item_id = ?");
                    $get_stmt->bind_param("i", $id);
                    $get_stmt->execute();
                    $get_result = $get_stmt->get_result();
                    $current_item = $get_result->fetch_assoc();
                    $get_stmt->close();
                    
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Item updated successfully (excluding cost and quantity)',
                        'item' => [
                            'item_id' => $id,
                            'stock_number' => $stock_number,
                            'item_name' => $item_name,
                            'description' => $description,
                            'unit' => $unit,
                            'reorder_point' => $reorder_point,
                            'unit_cost' => $current_item['unit_cost'], // Keep original values
                            'quantity_on_hand' => $current_item['quantity_on_hand'], // Keep original values
                            'has_multiple_entries' => true  // ADD THIS LINE
                        ]
                    ]);
                } else {
                        echo json_encode(['success' => false, 'message' => 'Error updating item']);
                    }
                } else {
                    // Update all fields including unit_cost and quantity_on_hand
                    $unit_cost = floatval($_POST['unit_cost']);
                    $quantity_on_hand = intval($_POST['quantity_on_hand']);
                    
                    $stmt = $conn->prepare("UPDATE items SET 
                                stock_number = ?,
                                item_name = ?,
                                description = ?,
                                unit = ?,
                                reorder_point = ?,
                                unit_cost = ?,
                                quantity_on_hand = ?
                            WHERE item_id = ?");
                    $stmt->bind_param("ssssiidi", $stock_number, $item_name, $description, $unit, $reorder_point, $unit_cost, $quantity_on_hand, $id);

                    if ($stmt->execute()) {
                        // Check if this item doesn't have an initial_quantity set yet
                        $check_initial_stmt = $conn->prepare("SELECT initial_quantity FROM items WHERE item_id = ?");
                        $check_initial_stmt->bind_param("i", $id);
                        $check_initial_stmt->execute();
                        $check_result = $check_initial_stmt->get_result();
                        $check_row = $check_result->fetch_assoc();
                        
                        // If initial_quantity is 0 or null, set it to the quantity_on_hand being saved
                        if (empty($check_row['initial_quantity'])) {
                            $set_initial_stmt = $conn->prepare("UPDATE items SET initial_quantity = ? WHERE item_id = ?");
                            $set_initial_stmt->bind_param("ii", $quantity_on_hand, $id);
                            $set_initial_stmt->execute();
                            $set_initial_stmt->close();
                        }
                        $check_initial_stmt->close();
                        // Log history
                        logItemHistory($conn, $id);
                        
                        echo json_encode([
                            'success' => true, 
                            'message' => 'Item updated successfully',
                            'item' => [
                                'item_id' => $id,
                                'stock_number' => $stock_number,
                                'item_name' => $item_name,
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
                }
                $stmt->close();
            }
            break;
        case 'check_entries':
            if (isset($_POST['item_id'])) {
                $item_id = intval($_POST['item_id']);
                
                // Check if item has initial quantity > 0
                $item_stmt = $conn->prepare("SELECT initial_quantity FROM items WHERE item_id = ?");
                $item_stmt->bind_param("i", $item_id);
                $item_stmt->execute();
                $item_result = $item_stmt->get_result();
                $item_row = $item_result->fetch_assoc();
                $has_initial = ($item_row['initial_quantity'] > 0);
                
                // Check if item has inventory entries
                $entry_stmt = $conn->prepare("SELECT COUNT(*) as entry_count FROM inventory_entries WHERE item_id = ?");
                $entry_stmt->bind_param("i", $item_id);
                $entry_stmt->execute();
                $entry_result = $entry_stmt->get_result();
                $entry_row = $entry_result->fetch_assoc();
                $has_entries = ($entry_row['entry_count'] > 0);
                
                // Has multiple entries if both initial quantity and inventory entries exist
                $hasMultipleEntries = ($has_initial && $has_entries);
                
                echo json_encode(['hasMultipleEntries' => $hasMultipleEntries]);
                
                $item_stmt->close();
                $entry_stmt->close();
            }
            break;
        case 'clear_entries':
            if (isset($_POST['item_id'])) {
                $item_id = intval($_POST['item_id']);
                
                // First, get the initial quantity and original unit cost to restore
                $item_stmt = $conn->prepare("SELECT initial_quantity, unit_cost FROM items WHERE item_id = ?");
                $item_stmt->bind_param("i", $item_id);
                $item_stmt->execute();
                $item_result = $item_stmt->get_result();
                $item_row = $item_result->fetch_assoc();
                $initial_quantity = $item_row['initial_quantity'];
                $original_unit_cost = $item_row['unit_cost'];
                
                // Delete all inventory entries
                $delete_stmt = $conn->prepare("DELETE FROM inventory_entries WHERE item_id = ?");
                $delete_stmt->bind_param("i", $item_id);
                
                if ($delete_stmt->execute()) {
                    // Reset quantity to initial quantity, unit cost to original, and clear average_unit_cost
                    $update_stmt = $conn->prepare("UPDATE items SET quantity_on_hand = ?, unit_cost = ?, average_unit_cost = NULL WHERE item_id = ?");
                    $update_stmt->bind_param("idi", $initial_quantity, $original_unit_cost, $item_id);
                    
                    if ($update_stmt->execute()) {
                        //Log History
                        logItemHistory($conn, $item_id, 'cleared');
                        echo json_encode(['success' => true, 'message' => 'All entries cleared successfully']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error updating item quantities']);
                    }
                    $update_stmt->close();
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error clearing entries']);
                }
                
                $item_stmt->close();
                $delete_stmt->close();
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
    <h2><i class=""></i> Office Supplies</h2>

    <div class="search-container">
        <button class="add-btn" onclick="document.getElementById('addModal').style.display='block'">
            <i class="fas fa-plus"></i> Add New Item
        </button>
        <input type="text" id="searchInput" class="search-input" placeholder="Search by stock number, description, or unit...">
    </div>

    <div class="table-container">
        <table id="inventoryTable">
            <thead>
                <tr>
                    <th><i class=""></i> Stock Number</th>
                    <th><i class=""></i> Item</th>
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
                $sql = "SELECT i.*, 
                        (i.quantity_on_hand * CASE 
                            WHEN (i.initial_quantity > 0 AND (SELECT COUNT(*) FROM inventory_entries ie WHERE ie.item_id = i.item_id) > 0)
                            THEN ((i.initial_quantity * i.unit_cost) + COALESCE((SELECT SUM(ie.quantity * ie.unit_cost) FROM inventory_entries ie WHERE ie.item_id = i.item_id), 0)) / (i.initial_quantity + COALESCE((SELECT SUM(ie.quantity) FROM inventory_entries ie WHERE ie.item_id = i.item_id), 0))
                            ELSE i.unit_cost 
                        END) as total_cost,
                        (SELECT COUNT(*) FROM inventory_entries ie WHERE ie.item_id = i.item_id) as entry_count,
                        CASE 
                            WHEN (i.initial_quantity > 0 AND (SELECT COUNT(*) FROM inventory_entries ie WHERE ie.item_id = i.item_id) > 0)
                            THEN ((i.initial_quantity * i.unit_cost) + COALESCE((SELECT SUM(ie.quantity * ie.unit_cost) FROM inventory_entries ie WHERE ie.item_id = i.item_id), 0)) / (i.initial_quantity + COALESCE((SELECT SUM(ie.quantity) FROM inventory_entries ie WHERE ie.item_id = i.item_id), 0))
                            ELSE i.unit_cost 
                        END as calculated_average_cost,
                        CASE 
                            WHEN (i.initial_quantity > 0 AND (SELECT COUNT(*) FROM inventory_entries ie WHERE ie.item_id = i.item_id) > 0)
                            THEN 1
                            ELSE 0 
                        END as has_multiple_entries
                        FROM items i 
                        ORDER BY i.stock_number ASC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $total_cost = $row["total_cost"];
                        $status_class = $row["quantity_on_hand"] <= $row["reorder_point"] ? 'status-low' : 'status-normal';
                        
                        echo "<tr data-id='{$row['item_id']}'>
                            <td><strong>{$row['stock_number']}</strong></td>
                            <td>{$row['item_name']}</strong></td>
                            <td>{$row['description']}</td>
                            <td>{$row['unit']}</td>
                            <td class='quantity-cell'>
                                <div class='main-quantity'>
                                    <span class='" . ($row['quantity_on_hand'] <= $row['reorder_point'] ? 'status-low' : 'status-normal') . "'>
                                        " . $row['quantity_on_hand'] . "
                                    </span>
                                </div>
                                <div class='sub-entries' id='sub-entries-{$row['item_id']}'>
                                    <!-- Sub-entries will be loaded here -->
                                </div>
                            </td>
                            <td class='cost-cell'>
                            <div class='main-cost'>₱ " . number_format(($row['has_multiple_entries'] ? $row['calculated_average_cost'] : $row['unit_cost']), 2) . " " . ($row['has_multiple_entries'] ? '(average)' : '') . "</div>                                
                            <div class='sub-entries' id='sub-cost-{$row['item_id']}'>
                                    <!-- Sub-entries will be loaded here -->
                                </div>
                            </td>
                            <td class='currency'>₱ " . number_format($total_cost, 2) . "</td>
                            <td>{$row['reorder_point']}</td>
                            <td>" . date('M d, Y H:i', strtotime($row['created_at'] ?? 'now')) . "</td>
                            <td>
                                <button 
                                    class='btn edit-btn' 
                                    onclick='openEditModal(this)'
                                    data-id='{$row['item_id']}'
                                    data-stock_number='{$row['stock_number']}'
                                    data-item_name= '{$row['item_name']}'
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
                                
                                " . ($row['has_multiple_entries'] ? "
                                <button 
                                    class='btn clear-entries-btn' 
                                    onclick='clearEntries({$row['item_id']})' 
                                    title='Clear All Entries'
                                >
                                    <i class='fas fa-broom'></i> Clear Entries
                                </button>
                                " : "") . "
                            </td>
                        </tr>";

                        // Add sub-entries query and display
                        $sub_sql = "SELECT quantity, unit_cost, created_at FROM inventory_entries WHERE item_id = {$row['item_id']} ORDER BY created_at DESC";
                        $sub_result = $conn->query($sub_sql);

                        // Show sub-entries only if there are inventory entries
                        $total_entries = $sub_result->num_rows;
                        if ($total_entries > 0) {
                            echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const subQtyContainer = document.getElementById('sub-entries-{$row['item_id']}');
                                const subCostContainer = document.getElementById('sub-cost-{$row['item_id']}');
                                let subQtyHTML = '';
                                let subCostHTML = '';
                            
                                // Add original quantity first (only when entries exist)
                                if ({$row['initial_quantity']} > 0) {
                                    subQtyHTML += '<div class=\"sub-entry initial\">{$row['initial_quantity']} (initial)</div>';
                                    // Only show initial cost breakdown when there are multiple entries
                                    if ({$row['has_multiple_entries']}) {
                                        subCostHTML += '<div class=\"sub-entry initial\">₱ " . number_format($row['unit_cost'], 2) . " (initial)</div>';
                                    }
                                }";
                            
                            // Add inventory entries
                            $sub_result->data_seek(0); // Reset result pointer
                            while ($sub_row = $sub_result->fetch_assoc()) {
                                echo "
                                subQtyHTML += '<div class=\"sub-entry\">{$sub_row['quantity']}</div>';
                                subCostHTML += '<div class=\"sub-entry\">₱ " . number_format($sub_row['unit_cost'], 2) . "</div>';";
                            }
                            
                            echo "
                                subQtyContainer.innerHTML = subQtyHTML;
                                subCostContainer.innerHTML = subCostHTML;
                            });
                            </script>";
                        }
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
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h3><i class="fas fa-plus-circle"></i> Add New Item</h3>
        <form id="addForm">
            <label for="add_stock_number">Stock Number</label>
            <input type="text" name="stock_number" id="add_stock_number" placeholder="Enter stock number" required>
            <div id="stock_status" class="stock-status"></div>

            <label for="add_item_name">Item Name</label>
            <input type="text" name="item_name" id="add_item_name" placeholder="Name" required readonly>

            <label for="add_description">Description</label>
            <input type="text" name="description" id="add_description" placeholder="Description" required readonly>

            <label for="add_unit">Unit (pcs, box, etc.)</label>
            <input type="text" name="unit" id="add_unit" placeholder="Unit (pcs, box, etc.)" required readonly>

            <label for="add_reorder_point">Reorder Point</label>
            <input type="number" name="reorder_point" id="add_reorder_point" placeholder="Reorder Point" required min="0" readonly>

            <label for="add_unit_cost">Unit Cost (₱)</label>
            <input type="number" step="0.01" name="unit_cost" id="add_unit_cost" placeholder="Unit Cost (₱)" required min="0" readonly>

            <label for="add_quantity_on_hand">Quantity on Hand</label>
            <input type="number" name="quantity_on_hand" id="add_quantity_on_hand" placeholder="Quantity on Hand" required min="0">
            
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

            <label for="edit_stock_number">Stock Number</label>
            <input type="text" name="stock_number" id="edit_stock_number" placeholder="e.g. 1001" required>

            <label for="edit_item_name">Item Name</label>
            <input type="text" name="item_name" id="edit_item_name" placeholder="e.g Hammer" required>

            <label for="edit_description">Description</label>
            <input type="text" name="description" id="edit_description" placeholder="e.g. Red" required>

            <label for="edit_unit">Unit</label>
            <input type="text" name="unit" id="edit_unit" placeholder="pcs, box, etc." required>

            <label for="edit_reorder_point">Reorder Point</label>
            <input type="number" name="reorder_point" id="edit_reorder_point" placeholder="e.g. 10" required min="0">

            <label for="edit_unit_cost">Unit Cost (₱)</label>
            <input type="number" step="0.01" name="unit_cost" id="edit_unit_cost" placeholder="e.g. 25.00" required min="0">

            <label for="edit_quantity_on_hand">Quantity on Hand</label>
            <input type="number" name="quantity_on_hand" id="edit_quantity_on_hand" placeholder="e.g. 100" required min="0">
            
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