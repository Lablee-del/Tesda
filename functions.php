<?php 

function updateAverageCost($conn, $item_id) {
    $avg_stmt = $conn->prepare("
        UPDATE items i 
        SET average_unit_cost = (
            CASE 
                WHEN (i.initial_quantity > 0 AND (SELECT COUNT(*) FROM inventory_entries ie WHERE ie.item_id = i.item_id) > 0)
                THEN ((i.initial_quantity * i.unit_cost) + COALESCE((SELECT SUM(ie.quantity * ie.unit_cost) FROM inventory_entries ie WHERE ie.item_id = i.item_id), 0)) / (i.initial_quantity + COALESCE((SELECT SUM(ie.quantity) FROM inventory_entries ie WHERE ie.item_id = i.item_id), 0))
                ELSE i.unit_cost 
            END
        )
        WHERE item_id = ?
    ");
    $avg_stmt->bind_param("i", $item_id);
    $avg_stmt->execute();
    $avg_stmt->close();
}

function logItemHistory($conn, $item_id, $change_type = 'update') {
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

    // Get previous quantity (from latest history or initial_quantity fallback)
    $prev_stmt = $conn->prepare("SELECT quantity_on_hand FROM item_history WHERE item_id = ? ORDER BY changed_at DESC LIMIT 1");
    $prev_stmt->bind_param("i", $item_id);
    $prev_stmt->execute();
    $prev_result = $prev_stmt->get_result();
    $prev_row = $prev_result->fetch_assoc();
    $prev_stmt->close();

    $previous_quantity = isset($prev_row['quantity_on_hand']) 
        ? intval($prev_row['quantity_on_hand']) 
        : (isset($item['initial_quantity']) ? intval($item['initial_quantity']) : 0);

    // Current quantity (what's in the items table right now)
    $current_quantity = intval($item['quantity_on_hand']);

    // If clearing entries, we want to log BEFORE reset
    if ($change_type === 'cleared') {
        // Simulate what the new quantity will be after clearing
        $current_quantity = intval($item['initial_quantity']);
    }

    // Calculate change in quantity
    $quantity_change = $current_quantity - $previous_quantity;

    // Determine change direction
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
?>