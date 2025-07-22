<?php
require 'config.php';
include 'sidebar.php';

// Fetch inventory items from database
$inventory_items = [];
$sql = "SELECT description, stock_number FROM items ORDER BY description";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $inventory_items[] = $row;
    }
} else {
    // Handle database error
    error_log("Database error: " . $conn->error);
    $inventory_items = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPCI - Report on Physical Count of Inventories</title>
    <link rel="stylesheet" href="css/styles.css?v=<?= time() ?>">
</head>
<body class="rpci-page">
    <div class="content">
        <div class="rpci-form">
            <div class="rpci-header">
                <h2>Report on the Physical Count of Inventories</h2>
                <div class="form-subtitle">(Type of Inventory Item)</div>

                <div class="rpci-meta">
                    <div class="rpci-meta-row">
                        <label for="report_date">As at:</label>
                        <input type="date" id="report_date" name="report_date" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
            </div>
            
            <form method="POST" action="">
                <div class="form-fields">
                    <div class="field-group">
                        <label for="fund_cluster">Fund Cluster:
                            <input type="text" id="fund_cluster" name="fund_cluster" placeholder="Enter Fund Cluster">
                        </label>
                        
                    </div>

                    <div class="field-group" style="grid-column: 1 / -1;">
                        <label>For which: 
                            <input type="text" id="accountable_officer" name="accountable_officer" placeholder="Name of Accountable Officer">,    
                            <input type="text" id="official_designation" name="official_designation" placeholder="Official Designation">,
                            <input type="text" id="entity_name" name="entity_name" placeholder="Entity Name">
                            is accountable, having assumed such accountability on
                            <input type="date" id="assumption_date" name="assumption_date">
                            .
                            </label>
                        </div>
                    </div>
                </div>

                <table class="rpci-table">
                    <thead>
                        <tr>
                            <th rowspan="2">Article</th>
                            <th rowspan="2">Description</th>
                            <th rowspan="2">Stock Number</th>
                            <th rowspan="2">Unit of Measure</th>
                            <th rowspan="2">Unit Value</th>
                            <th rowspan="2">Balance Per Card<br>(Quantity)</th>
                            <th rowspan="2">On Hand Per Count<br>(Quantity)</th>
                            <th colspan="2">Shortage/Overage</th>
                            <th rowspan="2">Remarks</th>
                        </tr>
                        <tr>
                            <th>Quantity</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (empty($inventory_items)) {
                            echo '<tr><td colspan="10" style="text-align: center; padding: 40px; color: var(--text-gray); font-style: italic;">No inventory items found</td></tr>';
                        } else {
                            foreach ($inventory_items as $item) {
                                echo '<tr>';
                                // Article column with default value "Office Supplies"
                                echo '<td>Office Supplies</td>';
                                // Description from database
                                echo '<td>' . htmlspecialchars($item['description'] ?? '') . '</td>';
                                // Stock Number from database
                                echo '<td>' . htmlspecialchars($item['stock_number'] ?? '') . '</td>';
                                // Empty cells for manual input or future database integration
                                echo '<td></td>'; // Unit of Measure
                                echo '<td class="currency"></td>'; // Unit Value
                                echo '<td></td>'; // Balance Per Card
                                echo '<td></td>'; // On Hand Per Count
                                echo '<td></td>'; // Shortage/Overage Quantity
                                echo '<td class="currency"></td>'; // Shortage/Overage Value
                                echo '<td></td>'; // Remarks
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
                
                <div class="signature-section">
                    <div class="signature-box">
                        <h4>Certified Correct by:</h4>
                        <input type="text" class="signature-input" name="signature_name_1" placeholder="Signature over Printed Name">
                        <div class="signature-text">
                            Signature over Printed Name of Inventory<br>
                            Committee Chair and Members
                        </div>
                    </div>
                    
                    <div class="signature-box">
                        <h4>Approved by:</h4>
                        <input type="text" class="signature-input" name="signature_name_2" placeholder="Signature over Printed Name">
                        <div class="signature-text">
                            Signature over Printed Name of Head of Agency/Entity<br>
                            or Authorized Representative
                        </div>
                    </div>
                    
                    <div class="signature-box">
                        <h4>Verified by:</h4>
                        <input type="text" class="signature-input" name="signature_name_3" placeholder="Signature over Printed Name">
                        <div class="signature-text">
                            Signature over Printed Name of COA Representative
                        </div>
                    </div>
                </div>
                
                <div style="text-align: center; margin-top: 30px;">
                    <button type="button" class="export-btn" onclick="exportToPDF()">
                        📄 Export to PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function exportToPDF() {
            // PDF export functionality will be implemented later
            alert('Export to PDF functionality will be implemented');
        }
    </script>
</body>
</html>