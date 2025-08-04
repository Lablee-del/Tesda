<?php
require 'config.php';


// Fetch inventory items from database
$inventory_items = [];
$sql = "SELECT item_name, description, stock_number FROM items ORDER BY item_name";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $inventory_items[] = $row;
    }
} else {
    error_log("Database error: " . $conn->error);
}

$report_date = htmlspecialchars($_GET['report_date'] ?? date('Y-m-d'));
$fund_cluster = htmlspecialchars($_GET['fund_cluster'] ?? '');
$accountable_officer = htmlspecialchars($_GET['accountable_officer'] ?? '');
$official_designation = htmlspecialchars($_GET['official_designation'] ?? '');
$entity_name = htmlspecialchars($_GET['entity_name'] ?? '');
$assumption_date = htmlspecialchars($_GET['assumption_date'] ?? '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RPCI Report - Export</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Print-specific */
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 10px; }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.2;
            margin: 0;
            padding: 20px;
            background: #fff;
            color: #000;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 16px;
            box-sizing: border-box;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin: 0;
        }

        .subtitle {
            text-align: center;
            font-style: italic;
            margin: 4px 0 12px;
            font-size: 12px;
        }

        .meta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .meta .block {
            flex: 1 1 200px;
        }

        .accountability {
            font-size: 10px;
            margin-bottom: 12px;
        }

        .table-wrapper {
            overflow: visible;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }

        th {
            background: #e5e5e5;
            text-align: center;
            font-weight: bold;
        }

        .small {
            font-size: 9px;
        }

        .signatures {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        .signatures td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
            height: 100px;
            font-size: 10px;
        }

        .sig-title {
            font-weight: bold;
            margin-bottom: 4px;
        }

        .sig-name {
            border-bottom: 1px solid #000;
            min-height: 20px;
            margin-bottom: 6px;
        }

        .instructions {
            background: #fffacd;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 16px;
            border-radius: 4px;
        }

        .btn { 
            display: inline-block;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
        }

        .btn-print {
            background: #007cba;
            color: #fff;
        }

        .btn-back {
            background: #6c757d;
            color: #fff;
        }

        .label-inline { font-weight: bold; }
    </style>
</head>
<body>
    <div class="no-print">
        <div class="instructions">
            <h3>📄 Export Instructions</h3>
            <p><strong>To save as PDF:</strong></p>
            <ol>
                <li>Click the "Print/Save as PDF" button below.</li>
                <li>In the print dialog, choose "Save as PDF" or equivalent.</li>
                <li>Save to your desired location.</li>
            </ol>
            <p><strong>Best viewed in:</strong> Chrome or Edge for consistent PDF output.</p>
        </div>
        <button class="btn btn-print" onclick="window.print()">🖨️ Print/Save as PDF</button>
        <a href="rpci.php" class="btn btn-back" style="margin-left:8px;">← Back to Form</a>
        <hr style="margin: 20px 0;">
    </div>

    <div class="container">
        <div>
            <p class="title">REPORT ON THE PHYSICAL COUNT OF INVENTORIES</p>
            <p class="subtitle">(Type of Inventory Item)</p>
        </div>

        <div class="meta">
            <div class="block"><span class="label-inline">As at:</span> <?= $report_date ?></div>
            <div class="block"><span class="label-inline">Fund Cluster:</span> <?= $fund_cluster ?></div>
        </div>

        <div class="accountability">
            For which: <strong><?= $accountable_officer ?></strong>, <em>(Official Designation)</em> <strong><?= $official_designation ?></strong>, <strong><?= $entity_name ?></strong> is accountable, having assumed such accountability on <strong><?= $assumption_date ?></strong>.
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th rowspan="2" style="width:8%;">Article</th>
                        <th rowspan="2" style="width:12%;">Item</th>
                        <th rowspan="2" style="width:18%;">Description</th>
                        <th rowspan="2" style="width:10%;">Stock Number</th>
                        <th rowspan="2" style="width:8%;">Unit of Measure</th>
                        <th rowspan="2" style="width:8%;">Unit Value</th>
                        <th rowspan="2" style="width:8%;">Balance Per Card<br>(Quantity)</th>
                        <th rowspan="2" style="width:8%;">On Hand Per Count<br>(Quantity)</th>
                        <th colspan="2" style="width:12%;">Shortage/Overage</th>
                        <th rowspan="2" style="width:8%;">Remarks</th>
                    </tr>
                    <tr>
                        <th style="width:6%;">Quantity</th>
                        <th style="width:6%;">Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $row_count = 0;
                    if (!empty($inventory_items)) {
                        foreach ($inventory_items as $item) {
                            echo '<tr>';
                            echo '<td>Office Supplies</td>';
                            echo '<td>' . htmlspecialchars($item['item_name'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($item['description'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($item['stock_number'] ?? '') . '</td>';
                            echo '<td>&nbsp;</td>'; // unit
                            echo '<td>&nbsp;</td>'; // unit value
                            echo '<td>&nbsp;</td>'; // balance per card
                            echo '<td>&nbsp;</td>'; // on hand
                            echo '<td>&nbsp;</td>'; // shortage qty
                            echo '<td>&nbsp;</td>'; // shortage value
                            echo '<td>&nbsp;</td>'; // remarks
                            echo '</tr>';
                            $row_count++;
                        }
                    }
                    for ($i = $row_count; $i < 15; $i++) {
                        echo '<tr>';
                        for ($j = 0; $j < 12; $j++) {
                            echo '<td>&nbsp;</td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="signatures">
            <table>
                <tr>
                    <td>
                        <div class="sig-title">Certified Correct by:</div>
                        <div class="sig-name">&nbsp;</div>
                        <div class="small">Signature over Printed Name of Inventory Committee Chair and Members</div>
                    </td>
                    <td>
                        <div class="sig-title">Approved by:</div>
                        <div class="sig-name">&nbsp;</div>
                        <div class="small">Signature over Printed Name of Head of Agency/Entity or Authorized Representative</div>
                    </td>
                    <td>
                        <div class="sig-title">Verified by:</div>
                        <div class="sig-name">&nbsp;</div>
                        <div class="small">Signature over Printed Name of COA Representative</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>