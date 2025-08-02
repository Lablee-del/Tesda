<?php
require 'config.php';

if (!isset($_GET['item_id'])) {
    die("❌ Error: item not found.");
}

$item_id = (int)$_GET['item_id'];
if ($item_id <= 0) {
    die("❌ Invalid item ID.");
}

// Fetch item
$item_stmt = $conn->prepare("SELECT * FROM items WHERE item_id = ?");
$item_stmt->bind_param("i", $item_id);
$item_stmt->execute();
$item_result = $item_stmt->get_result();
if (!$item_result || $item_result->num_rows === 0) {
    die("❌ No record found for Item ID $item_id.");
}
$items = $item_result->fetch_assoc();
$item_stmt->close();

// Fetch history
$history_stmt = $conn->prepare("SELECT * FROM item_history WHERE item_id = ? ORDER BY changed_at DESC");
$history_stmt->bind_param("i", $item_id);
$history_stmt->execute();
$history_result = $history_stmt->get_result();
$history_rows = [];
if ($history_result && $history_result->num_rows > 0) {
    while ($row = $history_result->fetch_assoc()) {
        $history_rows[] = $row;
    }
}
$history_stmt->close();

// Entity (LGU)
$ris = ['entity_name' => 'TESDA'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Item ID: <?php echo htmlspecialchars($items['item_id']); ?> - Export</title>
  <style>
    @media print {
      .no-print { display:none !important; }
    }
    body {
      margin: 20px;
      font-family: "Times New Roman", serif;
      font-size: 12px;
      color: #000;
    }
    .card-wrapper {
      max-width: 1000px;
      margin: 0 auto;
      border: 2px solid #000;
      padding: 8px 12px 16px;
      position: relative;
    }
    .appendix {
      position: absolute;
      top: 8px;
      right: 12px;
      font-size: 11px;
      font-style: italic;
    }
    .title {
      text-align: center;
      font-weight: bold;
      font-size: 18px;
      margin: 4px 0 8px;
      letter-spacing: 1px;
    }
    .meta-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 6px;
    font-size: 12px;
    }

    .meta-table td {
        padding: 3px 6px;
        vertical-align: bottom;
    }
    
    .meta-label {
      font-weight: bold;
      white-space: nowrap;
      display: inline-block;
      vertical-align: bottom;
      margin-right: 4px;
    }

    .field-line {
      display: inline-block;
      border-bottom: 1px solid #000;
      min-width: 100px;
      min-height: 16px;
      line-height: 16px;
      padding: 0 2px;
      box-sizing: border-box;
      vertical-align: bottom;
    }
    .stock-card-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 4px;
      table-layout: fixed;
      font-size: 11px;
    }
    .stock-card-table th,
    .stock-card-table td {
      border: 1px solid #000;
      padding: 4px 6px;
      text-align: center;
      vertical-align: middle;
    }
    .stock-card-table th {
      font-weight: bold;
    }
    .sub-header {
      border-left: none;
      border-right: none;
      padding: 0;
    }
    .small {
      font-size: 10px;
    }
    .controls {
      margin-bottom: 12px;
    }
    .print-button {
      background: #007cba;
      color: white;
      padding: 6px 14px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 12px;
      margin-right: 6px;
      text-decoration: none;
    }
    .print-button:hover { filter: brightness(0.95); }
    .back-link {
      background: #6c757d;
      color: white;
      padding: 6px 14px;
      border-radius: 4px;
      font-size: 12px;
      text-decoration: none;
    }
    .instruction-box {
      background: #fffacd;
      border: 1px solid #ddd;
      padding: 8px;
      margin-bottom: 10px;
      border-radius: 4px;
      font-size: 12px;
    }
    .no-history {
      font-style: italic;
      color: #444;
    }
  </style>
</head>
<body>
  <div class="no-print">
    <div class="instruction-box">
      <strong>📄 Export Instructions:</strong>
      <div>1. Click the Print/Save button below.</div>
      <div>2. In the print dialog choose "Save as PDF" or printer of choice.</div>
      <div>3. Save.</div>
    </div>
    <button class="print-button" onclick="window.print()">🖨️ Print / Save as PDF</button>
    <a class="back-link" href="view_sc.php?item_id=<?php echo $item_id; ?>">← Back to Item No. <?php echo htmlspecialchars($items['item_id']); ?></a>
    <hr style="margin:14px 0;">
  </div>

  <div class="card-wrapper">
    <div class="appendix">Appendix 53</div>
    <div class="title">STOCK CARD</div>

    <table class="meta-table">
      <tr>
        <td style="width:33%;"><span class="meta-label">LGU:</span> <span class="field-line"><?php echo htmlspecialchars($ris['entity_name']); ?></span></td>
        <td style="width:33%;"><span class="meta-label">Fund:</span> <span class="field-line">______________</span></td>
        <td style="width:34%;"></td>
      </tr>
      <tr>
        <td><span class="meta-label">Item:</span> <span class="field-line"><?php echo htmlspecialchars($items['item_name']); ?></span></td>
        <td><span class="meta-label">Stock No.:</span> <span class="field-line"><?php echo htmlspecialchars($items['stock_number']); ?></span></td>
        <td></td>
      </tr>
      <tr>
        <td><span class="meta-label">Description:</span> <span class="field-line"><?php echo htmlspecialchars($items['description']); ?></span></td>
        <td><span class="meta-label">Re-order Point:</span> <span class="field-line"><?php echo htmlspecialchars($items['reorder_point']); ?></span></td>
        <td></td>
      </tr>
      <tr>
        <td><span class="meta-label">Unit of Measurement:</span> <span class="field-line"><?php echo htmlspecialchars($items['unit']); ?></span></td>
        <td colspan="2"></td>
      </tr>
    </table>

    <table class="stock-card-table">
      <thead>
        <tr>
          <th rowspan="2" style="width:10%;">Date</th>
          <th rowspan="2">Reference</th>
          <th colspan="1">Receipt</th>
          <th colspan="2">Issue</th>
          <th rowspan="2">Balance Qty.</th>
          <th rowspan="2">No. of Days to Consume</th>
        </tr>
        <tr>
          <th>Qty.</th>
          <th>Qty.</th>
          <th>Office</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($history_rows) > 0): ?>
          <?php foreach ($history_rows as $h):
            $date = date('M d, Y', strtotime($h['changed_at']));
            $reference = ucfirst(htmlspecialchars($h['change_type'])); 
            $receipt_qty = $h['quantity_change'] > 0 ? htmlspecialchars($h['quantity_change']) : '';
            $issue_qty = $h['quantity_change'] < 0 ? abs(htmlspecialchars($h['quantity_change'])) : '';
            $office = ''; // fill if you have office info
            $balance = htmlspecialchars($h['quantity_on_hand']);
            $days = '--'; // placeholder
          ?>
            <tr>
              <td><?php echo $date; ?></td>
              <td><?php echo $reference_id; ?></td>
              <td><?php echo $receipt_qty; ?></td>
              <td><?php echo $issue_qty; ?></td>
              <td><?php echo $office; ?></td>
              <td><?php echo $balance; ?></td>
              <td><?php echo $days; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="no-history">No history available for this item.</td>
          </tr>
        <?php endif; ?>
        <!-- fill blank rows to emulate the template if desired -->
        <?php for ($i = 0; $i < max(0, 20 - count($history_rows)); $i++): ?>
          <tr>
            <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
          </tr>
        <?php endfor; ?>
      </tbody>
    </table>
  </div>

</body>
</html>

<?php
$conn->close();
?>