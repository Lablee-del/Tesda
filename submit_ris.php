<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // RIS Header fields
    $ris_no = $_POST['ris_no'];
    $entity_name = $_POST['entity_name'];
    $fund_cluster = $_POST['fund_cluster'];
    $division = $_POST['division'];
    $office = $_POST['office'];
    $responsibility_center_code = $_POST['responsibility_center_code'];
    $date_requested = $_POST['date_requested'];
    $purpose = $_POST['purpose'];

    // Insert into ris table
    $stmt = $conn->prepare("INSERT INTO ris (ris_no, entity_name, fund_cluster, division, office, responsibility_center_code, date_requested, purpose)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $ris_no, $entity_name, $fund_cluster, $division, $office, $responsibility_center_code, $date_requested, $purpose);
    $stmt->execute();
    $ris_id = $stmt->insert_id;
    $stmt->close();

    // RIS Items arrays from form
    $stock_numbers = $_POST['stock_number'];
    $stock_availables = $_POST['stock_available'];
    $issued_quantities = $_POST['issued_quantity'];
    $remarks = $_POST['remarks'];

    // Insert items into ris_items and update inventory
    for ($i = 0; $i < count($stock_numbers); $i++) {
        $stock_no = $stock_numbers[$i];
        $stock_available = $stock_availables[$i];
        $issued_qty = (int)$issued_quantities[$i];
        $remark = $remarks[$i];

        $stmt = $conn->prepare("INSERT INTO ris_items (ris_id, stock_number, stock_available, issued_quantity, remarks)
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issis", $ris_id, $stock_no, $stock_available, $issued_qty, $remark);
        $stmt->execute();

        // Update inventory: deduct issued quantity
        $conn->query("UPDATE items SET quantity_on_hand = quantity_on_hand - $issued_qty WHERE stock_number = '$stock_no'");
    }

    header("Location: ris.php");
    exit();

} else {
    echo "Invalid request.";
}
