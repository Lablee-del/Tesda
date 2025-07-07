<?php include 'sidebar.php'; ?>
<?php require 'config.php'; ?>

<div class="content">
    <h2>Add RIS Form</h2>

    <form method="post" action="submit_ris.php" class="ris-form">
        <h3>RIS Details</h3>
        <div class="form-group">
            <label>Entity Name:</label>
            <input type="text" name="entity_name" required>

            <label>Fund Cluster:</label>
            <input type="text" name="fund_cluster">

            <label>Division:</label>
            <input type="text" name="division">

            <label>Office:</label>
            <input type="text" name="office">

            <label>Responsibility Center Code:</label>
            <input type="text" name="responsibility_center_code">

            <label>RIS No.:</label>
            <input type="text" name="ris_no">

            <label>Date:</label>
            <input type="date" name="date_requested" required>

            <label>Purpose:</label>
            <textarea name="purpose" rows="2"></textarea>
        </div>

        <h3>RIS Items</h3>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Stock No.</th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th>Quantity on Hand</th>
                        <th>Stock Available</th>
                        <th>Issued Qty</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $result = $conn->query("SELECT * FROM items");
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td><input type="hidden" name="stock_number[]" value="' . htmlspecialchars($row['stock_number']) . '">' . htmlspecialchars($row['stock_number']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['unit']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['quantity_on_hand']) . '</td>';
                            echo '<td>
                                    <select name="stock_available[]">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </td>';
                            echo '<td><input type="number" name="issued_quantity[]" min="0" max="' . htmlspecialchars($row['quantity_on_hand']) . '"></td>';
                            echo '<td><input type="text" name="remarks[]"></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7">No inventory items found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h3>Signatories</h3>
        <div class="form-group">
            <label>Requested by:</label>
            <input type="text" name="requested_by">

            <label>Approved by:</label>
            <input type="text" name="approved_by">

            <label>Issued by:</label>
            <input type="text" name="issued_by">

            <label>Received by:</label>
            <input type="text" name="received_by">
        </div>

        <button type="submit">Submit RIS</button>
    </form>
</div>

<style>
.content {
    margin-left: 250px;
    padding: 20px;
}
.ris-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.form-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.ris-form input, .ris-form textarea, .ris-form select {
    padding: 6px;
    width: 100%;
    box-sizing: border-box;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
}
th, td {
    padding: 6px;
    border: 1px solid #ccc;
}
button {
    padding: 8px 16px;
}
</style>
