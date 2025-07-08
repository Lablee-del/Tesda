<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit RIS - Inventory Management System</title>
    <link rel="stylesheet" href="css/styles.css?v=<?= time() ?>">
</head>
<body>

<?php include 'sidebar.php'; ?>
<?php require 'config.php'; ?>

<?php
if (!isset($_GET['ris_id'])) {
    die("RIS ID not specified.");
}
$ris_id = (int)$_GET['ris_id'];

// Fetch RIS header
$ris_result = $conn->query("SELECT * FROM ris WHERE ris_id = $ris_id");
if (!$ris_result || $ris_result->num_rows === 0) {
    die("RIS not found.");
}
$ris = $ris_result->fetch_assoc();

// Fetch RIS items
$item_result = $conn->query("SELECT * FROM ris_items WHERE ris_id = $ris_id");
?>

<div class="content edit-ris-page">
    <h2>Edit RIS - <?php echo htmlspecialchars($ris['ris_no']); ?></h2>

    <form method="post" action="update_ris.php">
        <input type="hidden" name="ris_id" value="<?php echo $ris_id; ?>">

        <label>Entity Name:</label>
        <input type="text" name="entity_name" value="<?php echo htmlspecialchars($ris['entity_name']); ?>" required>

        <label>Fund Cluster:</label>
        <input type="text" name="fund_cluster" value="<?php echo htmlspecialchars($ris['fund_cluster']); ?>" required>

        <label>Division:</label>
        <input type="text" name="division" value="<?php echo htmlspecialchars($ris['division']); ?>" required>

        <label>Office:</label>
        <input type="text" name="office" value="<?php echo htmlspecialchars($ris['office']); ?>" required>

        <label>Responsibility Center Code:</label>
        <input type="text" name="responsibility_center_code" value="<?php echo htmlspecialchars($ris['responsibility_center_code']); ?>" required>

        <label>Date:</label>
        <input type="date" name="date_requested" value="<?php echo htmlspecialchars($ris['date_requested']); ?>" required>

        <label>Purpose:</label>
        <textarea name="purpose" rows="3" required><?php echo htmlspecialchars($ris['purpose']); ?></textarea>

        <h3>Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Stock No.</th>
                    <th>Stock Available</th>
                    <th>Issued Quantity</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($item_result->num_rows > 0) {
                    while ($item = $item_result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td><input type="hidden" name="stock_number[]" value="' . htmlspecialchars($item['stock_number']) . '">' . htmlspecialchars($item['stock_number']) . '</td>';
                        echo '<td>
                                <select name="stock_available[]" required>
                                    <option value="Yes"' . ($item['stock_available'] == 'Yes' ? ' selected' : '') . '>Yes</option>
                                    <option value="No"' . ($item['stock_available'] == 'No' ? ' selected' : '') . '>No</option>
                                </select>
                              </td>';
                        echo '<td><input type="number" name="issued_quantity[]" value="' . htmlspecialchars($item['issued_quantity']) . '" min="0" required></td>';
                        echo '<td><input type="text" name="remarks[]" value="' . htmlspecialchars($item['remarks']) . '" placeholder="Enter remarks..."></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4" style="text-align: center; color: #666; font-style: italic;">No items found for this RIS.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <button type="submit">Save Changes</button>
    </form>
</div>

</body>
</html>