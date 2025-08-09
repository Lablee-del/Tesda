<?php 

function updateAverageCost($conn, $item_id) {
    // First, check if there are any inventory entries
    $check_stmt = $conn->prepare("SELECT COUNT(*) as entry_count FROM inventory_entries WHERE item_id = ?");
    $check_stmt->bind_param("i", $item_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $check_row = $check_result->fetch_assoc();
    $check_stmt->close();
    
    if ($check_row['entry_count'] == 0) {
        // No entries exist, clear calculated values
        $clear_stmt = $conn->prepare("UPDATE items SET calculated_unit_cost = NULL, calculated_quantity = NULL, average_unit_cost = NULL WHERE item_id = ?");
        $clear_stmt->bind_param("i", $item_id);
        $clear_stmt->execute();
        $clear_stmt->close();
    } else {
        // Calculate and store both average cost and total quantity
        $avg_stmt = $conn->prepare("
            UPDATE items i 
            SET 
                average_unit_cost = (
                    CASE 
                        WHEN (i.initial_quantity > 0 AND (SELECT COUNT(*) FROM inventory_entries ie WHERE ie.item_id = i.item_id) > 0)
                        THEN ((i.initial_quantity * i.unit_cost) + COALESCE((SELECT SUM(ie.quantity * ie.unit_cost) FROM inventory_entries ie WHERE ie.item_id = i.item_id), 0)) / (i.initial_quantity + COALESCE((SELECT SUM(ie.quantity) FROM inventory_entries ie WHERE ie.item_id = i.item_id), 0))
                        ELSE i.unit_cost 
                    END
                ),
                calculated_unit_cost = (
                    CASE 
                        WHEN (i.initial_quantity > 0 AND (SELECT COUNT(*) FROM inventory_entries ie WHERE ie.item_id = i.item_id) > 0)
                        THEN ((i.initial_quantity * i.unit_cost) + COALESCE((SELECT SUM(ie.quantity * ie.unit_cost) FROM inventory_entries ie WHERE ie.item_id = i.item_id), 0)) / (i.initial_quantity + COALESCE((SELECT SUM(ie.quantity) FROM inventory_entries ie WHERE ie.item_id = i.item_id), 0))
                        ELSE i.unit_cost 
                    END
                ),
                calculated_quantity = (
                    i.initial_quantity + COALESCE((SELECT SUM(ie.quantity) FROM inventory_entries ie WHERE ie.item_id = i.item_id), 0)
                )
            WHERE item_id = ?
        ");
        $avg_stmt->bind_param("i", $item_id);
        $avg_stmt->execute();
        $avg_stmt->close();
    }
}

function logItemHistory($conn, $item_id, ?int $quantity_change = null, string $change_type = 'update', ?int $ris_id = null) {
    // Fetch current item info
    $stmt = $conn->prepare("SELECT * FROM items WHERE item_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();

    if (!$item) {
        return; // No such item, skip logging
    }

    // Get previous quantity (latest history) or fallback to initial_quantity
    $prev_stmt = $conn->prepare("SELECT quantity_on_hand FROM item_history WHERE item_id = ? ORDER BY changed_at DESC LIMIT 1");
    $prev_stmt->bind_param("i", $item_id);
    $prev_stmt->execute();
    $prev_result = $prev_stmt->get_result();
    $prev_row = $prev_result->fetch_assoc();
    $prev_stmt->close();

    $previous_quantity = isset($prev_row['quantity_on_hand'])
        ? intval($prev_row['quantity_on_hand'])
        : (isset($item['initial_quantity']) ? intval($item['initial_quantity']) : 0);

    // Current quantity from items table
    $current_quantity = intval($item['quantity_on_hand']);

    // If quantity_change not provided, derive it
    if ($quantity_change === null) {
        $quantity_change = $current_quantity - $previous_quantity;
    }

    // Determine change direction
    $change_direction = match(true) {
        $quantity_change > 0 => 'increase',
        $quantity_change < 0 => 'decrease',
        default              => 'no_change'
    };

    // Insert into history, including ris_id if available
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
            change_type,
            ris_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    // Use item['unit_cost'] or maybe average_unit_cost depending on semantics
    $unit_cost = $item['unit_cost'];

    // ris_id may be null; bind_param requires a value, so coalesce to null
    $insert->bind_param(
        "issssidiissi",
        $item_id,
        $item['stock_number'],
        $item['item_name'],
        $item['description'],
        $item['unit'],
        $item['reorder_point'],
        $unit_cost,
        $current_quantity,
        $quantity_change,
        $change_direction,
        $change_type,
        $ris_id
    );

    $insert->execute();
    $insert->close();
}
?>