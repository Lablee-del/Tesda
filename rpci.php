<?php
require 'config.php';
include 'sidebar.php';
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
                        // Sample data - replace with database query
                        $inventory_items = [
                            // This will be populated from database
                        ];
                        
                        if (empty($inventory_items)) {
                            echo '<tr><td colspan="10" style="text-align: center; padding: 40px; color: var(--text-gray); font-style: italic;">No inventory items found</td></tr>';
                        } else {
                            foreach ($inventory_items as $item) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($item['article'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($item['description'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($item['stock_number'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($item['unit_of_measure'] ?? '') . '</td>';
                                echo '<td class="currency">₱' . number_format($item['unit_value'] ?? 0, 2) . '</td>';
                                echo '<td>' . htmlspecialchars($item['balance_per_card'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($item['on_hand_count'] ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($item['shortage_overage_qty'] ?? '') . '</td>';
                                echo '<td class="currency">₱' . number_format($item['shortage_overage_value'] ?? 0, 2) . '</td>';
                                echo '<td>' . htmlspecialchars($item['remarks'] ?? '') . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
                
                <div class="signature-section">
                    <div class="signature-box">
                        <h4>Certified Correct by:</h4>
                        <input type="text" class="signature-input" name="signature_name_X" placeholder="Signature over Printed Name">
                        <div class="signature-text">
                            Signature over Printed Name of Inventory<br>
                            Committee Chair and Members
                        </div>
                    </div>
                    
                    <div class="signature-box">
                        <h4>Approved by:</h4>
                        <input type="text" class="signature-input" name="signature_name_X" placeholder="Signature over Printed Name">
                        <div class="signature-text">
                            Signature over Printed Name of Head of Agency/Entity<br>
                            or Authorized Representative
                        </div>
                    </div>
                    
                    <div class="signature-box">
                        <h4>Verified by:</h4>
                        <input type="text" class="signature-input" name="signature_name_X" placeholder="Signature over Printed Name">
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