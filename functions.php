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
?>