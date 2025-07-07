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

<div class="content">
    <h2>Edit RIS - <?php echo htmlspecialchars($ris['ris_no']); ?></h2>

    <form method="post" action="update_ris.php">
        <input type="hidden" name="ris_id" value="<?php echo $ris_id; ?>">

        <label>Entity Name:</label>
        <input type="text" name="entity_name" value="<?php echo htmlspecialchars($ris['entity_name']); ?>">

        <label>Fund Cluster:</label>
        <input type="text" name="fund_cluster" value="<?php echo htmlspecialchars($ris['fund_cluster']); ?>">

        <label>Division:</label>
        <input type="text" name="division" value="<?php echo htmlspecialchars($ris['division']); ?>">

        <label>Office:</label>
        <input type="text" name="office" value="<?php echo htmlspecialchars($ris['office']); ?>">

        <label>Responsibility Center Code:</label>
        <input type="text" name="responsibility_center_code" value="<?php echo htmlspecialchars($ris['responsibility_center_code']); ?>">

        <label>Date:</label>
        <input type="date" name="date_requested" value="<?php echo htmlspecialchars($ris['date_requested']); ?>">

        <label>Purpose:</label>
        <textarea name="purpose" rows="2"><?php echo htmlspecialchars($ris['purpose']); ?></textarea>

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
                while ($item = $item_result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td><input type="hidden" name="stock_number[]" value="' . htmlspecialchars($item['stock_number']) . '">' . htmlspecialchars($item['stock_number']) . '</td>';
                    echo '<td>
                            <select name="stock_available[]">
                                <option value="Yes"' . ($item['stock_available'] == 'Yes' ? ' selected' : '') . '>Yes</option>
                                <option value="No"' . ($item['stock_available'] == 'No' ? ' selected' : '') . '>No</option>
                            </select>
                          </td>';
                    echo '<td><input type="number" name="issued_quantity[]" value="' . htmlspecialchars($item['issued_quantity']) . '"></td>';
                    echo '<td><input type="text" name="remarks[]" value="' . htmlspecialchars($item['remarks']) . '"></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>

        <button type="submit">Save Changes</button>
    </form>
</div>

<style>
.content {
    margin-left: 250px;
    padding: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    padding: 8px;
    border: 1px solid #ddd;
}
input, textarea, select {
    width: 100%;
    padding: 6px;
    margin: 4px 0 12px;
    box-sizing: border-box;
}
button {
    padding: 8px 16px;
}
</style>
