<?php include 'sidebar.php'; ?>
<?php require 'config.php'; ?>

<?php
if (!isset($_GET['ris_id'])) {
    die("❌ Error: RIS ID not specified in the URL.");
}

$ris_id = (int)$_GET['ris_id'];
echo "<p>🔍 Looking for RIS ID: $ris_id</p>";

// Fetch RIS header
$ris_result = $conn->query("SELECT * FROM ris WHERE ris_id = $ris_id");

if (!$ris_result) {
    die("❌ Database error: " . $conn->error);
}

if ($ris_result->num_rows === 0) {
    die("❌ No RIS record found for RIS ID: $ris_id");
}

$ris = $ris_result->fetch_assoc();

// Fetch RIS items
$item_result = $conn->query("SELECT * FROM ris_items WHERE ris_id = $ris_id");
?>

<div class="content">
    <h2>View RIS - <?php echo htmlspecialchars($ris['ris_no']); ?></h2>

    <div class="ris-details">
        <p><strong>Entity Name:</strong> <?php echo htmlspecialchars($ris['entity_name']); ?></p>
        <p><strong>Fund Cluster:</strong> <?php echo htmlspecialchars($ris['fund_cluster']); ?></p>
        <p><strong>Division:</strong> <?php echo htmlspecialchars($ris['division']); ?></p>
        <p><strong>Office:</strong> <?php echo htmlspecialchars($ris['office']); ?></p>
        <p><strong>Responsibility Center Code:</strong> <?php echo htmlspecialchars($ris['responsibility_center_code']); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($ris['date_requested']); ?></p>
        <p><strong>Purpose:</strong> <?php echo htmlspecialchars($ris['purpose']); ?></p>
    </div>

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
            if ($item_result && $item_result->num_rows > 0) {
                while ($item = $item_result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($item['stock_number']) . '</td>';
                    echo '<td>' . htmlspecialchars($item['stock_available']) . '</td>';
                    echo '<td>' . htmlspecialchars($item['issued_quantity']) . '</td>';
                    echo '<td>' . htmlspecialchars($item['remarks']) . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="4">No items found for this RIS.</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <h3>Signatories</h3>
    <div class="ris-details">
        <p><strong>Requested by:</strong> <?php echo htmlspecialchars($ris['requested_by']); ?></p>
        <p><strong>Approved by:</strong> <?php echo htmlspecialchars($ris['approved_by']); ?></p>
        <p><strong>Issued by:</strong> <?php echo htmlspecialchars($ris['issued_by']); ?></p>
        <p><strong>Received by:</strong> <?php echo htmlspecialchars($ris['received_by']); ?></p>
    </div>
</div>

<style>
.content {
    margin-left: 250px;
    padding: 20px;
}
.ris-details p {
    margin: 4px 0;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    padding: 10px;
    border: 1px solid #ddd;
}
</style>
