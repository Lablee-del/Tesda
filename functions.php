<?php 

function updateAverageCost($conn, $item_id) {
    // Get totals for positive qty (for average) and all qty (for current stock)
    $sql = "
        SELECT 
            (i.initial_quantity * i.unit_cost) 
                + COALESCE(SUM(CASE WHEN ie.quantity > 0 THEN ie.quantity * ie.unit_cost ELSE 0 END), 0) AS total_cost_for_avg,
            (i.initial_quantity) 
                + COALESCE(SUM(CASE WHEN ie.quantity > 0 THEN ie.quantity ELSE 0 END), 0) AS total_qty_for_avg,
            (i.initial_quantity) 
                + COALESCE(SUM(ie.quantity), 0) AS total_qty_all
        FROM items i
        LEFT JOIN inventory_entries ie ON i.item_id = ie.item_id
        WHERE i.item_id = ?
        GROUP BY i.item_id
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    if (!$data || $data['total_qty_all'] <= 0) {
        // No stock left — reset average
        $reset = $conn->prepare("
            UPDATE items 
            SET calculated_unit_cost = NULL, calculated_quantity = NULL, average_unit_cost = NULL
            WHERE item_id = ?
        ");
        $reset->bind_param("i", $item_id);
        $reset->execute();
        $reset->close();
        return;
    }

    // Weighted average cost uses only positive qty
    $avg_cost = ($data['total_qty_for_avg'] > 0) 
        ? $data['total_cost_for_avg'] / $data['total_qty_for_avg'] 
        : 0;

    // Update items table — store full precision average, real current qty
    $update = $conn->prepare("
        UPDATE items
        SET 
            average_unit_cost = ?,
            calculated_unit_cost = ?,
            calculated_quantity = ?
        WHERE item_id = ?
    ");
    $update->bind_param("ddii", $avg_cost, $avg_cost, $data['total_qty_all'], $item_id);
    $update->execute();
    $update->close();
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

function updateLastHistoryEntry($conn, $item_id, $change_type, $quantity = null) {
    // Get latest history entry for this item
    $stmt = $conn->prepare("
        SELECT history_id 
        FROM item_history 
        WHERE item_id = ? 
        ORDER BY changed_at DESC 
        LIMIT 1
    ");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $last = $result->fetch_assoc();
    $stmt->close();

    if ($last) {
        // Update that entry
        $stmt = $conn->prepare("
            UPDATE item_history 
            SET change_type = ?, quantity = ?, changed_at = NOW()
            WHERE history_id = ?
        ");
        $stmt->bind_param("sii", $change_type, $quantity, $last['history_id']);
        $stmt->execute();
        $stmt->close();
        return true;
    } else {
        // If no entry exists, fall back to inserting
        logItemHistory($conn, $item_id, $quantity, $change_type);
        return false;
    }
}
?>