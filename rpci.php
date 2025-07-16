<?php
include 'config.php';
include 'sidebar.php';
// Fetch RIS data
$ris_query = "SELECT * FROM ris";
$ris_result = mysqli_query($conn, $ris_query);

// Fetch RSMI data
$rsmi_query = "SELECT * FROM rsmi";
$rsmi_result = mysqli_query($conn, $rsmi_query);

// Combine results into an array
$inventory = [];
while ($row = mysqli_fetch_assoc($ris_result)) {
    $inventory[] = $row;
}
while ($row = mysqli_fetch_assoc($rsmi_result)) {
    $inventory[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report on the Physical Count of Inventories</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; }
        th, td { padding: 5px; text-align: center; }
        .header { font-weight: bold; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">REPORT ON THE PHYSICAL COUNT OF INVENTORIES</h2>
    <!-- Add your header fields here as in the screenshot -->

    <table width="100%">
        <tr class="header">
            <th>Article</th>
            <th>Description</th>
            <th>Stock Number</th>
            <th>Unit of Measure</th>
            <th>Unit Value</th>
            <th>Balance Per Card (Qty)</th>
            <th>On Hand Per Count (Qty)</th>
            <th>Shortage/Overage (Qty)</th>
            <th>Shortage/Overage (Value)</th>
            <th>Remarks</th>
        </tr>
        <?php foreach ($inventory as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['article'] ?? '') ?></td>
            <td><?= htmlspecialchars($item['description'] ?? '') ?></td>
            <td><?= htmlspecialchars($item['stock_number'] ?? '') ?></td>
            <td><?= htmlspecialchars($item['unit_measure'] ?? '') ?></td>
            <td><?= htmlspecialchars($item['unit_value'] ?? '') ?></td>
            <td><?= htmlspecialchars($item['balance_per_card'] ?? '') ?></td>
            <td><?= htmlspecialchars($item['on_hand_per_count'] ?? '') ?></td>
            <td><?= htmlspecialchars($item['shortage_overage_qty'] ?? '') ?></td>
            <td><?= htmlspecialchars($item['shortage_overage_value'] ?? '') ?></td>
            <td><?= htmlspecialchars($item['remarks'] ?? '') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <!-- Add signature/footer section as in the screenshot -->
</body>
</html>