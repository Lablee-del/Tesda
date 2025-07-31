<?php include 'sidebar.php'; ?>
<?php require 'config.php'; 
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_history']) && isset($_POST['item_id'])) {
        $item_id_to_clear = (int)$_POST['item_id'];
        $stmt = $conn->prepare("DELETE FROM item_history WHERE item_id = ?");
        $stmt->bind_param("i", $item_id_to_clear);

        if ($stmt->execute()) {
            header("Location: view_sc.php?item_id=" . $item_id_to_clear . "&cleared=1");
            exit;
        } else {
            echo "<script>alert('❌ Failed to clear history');</script>";
        }
        $stmt->close();
    }

    if (isset($_GET['cleared']) && $_GET['cleared'] == 1) {
        echo "<script>alert('✅ History cleared successfully');</script>";
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View SC - TESDA Inventory System</title>
    <link rel="stylesheet" href="css/styles.css?v=<?= time() ?>">
</head>
<body class="view-sc-page">


    <?php
        if (!isset($_GET['item_id'])) {
            die("❌ Error: item not found.");
        }

        $item_id = (int)$_GET['item_id'];
        if ($item_id <= 0) {
            die("❌ Invalid item ID.");
        }

        $result = $conn->query("SELECT * FROM items WHERE item_id = $item_id");

        if (!$result) {
            die("❌ Database error: " . $conn->error);
        }

        if ($result->num_rows === 0) {
            die("❌ No record found for Item ID $item_id.");
        }

        $items = $result->fetch_assoc();

        $history_sql = "SELECT * FROM item_history WHERE item_id = $item_id ORDER BY changed_at DESC";
        $history_result = $conn->query($history_sql);

        $history_rows = [];
        if ($history_result && $history_result->num_rows > 0) {
            while ($row = $history_result->fetch_assoc()) {
                $history_rows[] = $row;
            }
        }
    ?>

    <div class="content">
        <h2>📋 Viewing Item No. <?php echo htmlspecialchars($items['item_id']); ?></h2>

        <div class="ris-actions">
            <a href="SC.php" class="btn btn-secondary">← Back to SC List</a>
            <a href="sc_export.php?item_id=<?php echo $item_id; ?>" class="btn btn-primary">📄 Export PDF</a>

            <form method="POST" 
                action="" 
                onsubmit="return confirm('Are you sure you want to delete this item\'s history?')" 
                style="display:inline;">
                <input type="hidden" name="clear_history" value="1">
                <input type="hidden" name="item_id" value="<?= $item_id ?>">
                <button type="submit" class="btn btn-danger">🗑️ Clear History</button>
            </form>
        </div>

        <div class="ris-details">
            <p><strong>Entity Name:</strong> <?php echo "TESDA"; ?></p>
            <p><strong>Item:</strong> <?php echo htmlspecialchars($items['description']); ?></p>
            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($items['quantity_on_hand']); ?></p>
            <p><strong>Stock No.:</strong> <?php echo htmlspecialchars($items['stock_number']); ?></p>
            <p><strong>Unit of Measurement:</strong> <?php echo htmlspecialchars($items['unit']); ?></p>
            <p><strong>Re-order Point:</strong> <?php echo htmlspecialchars($items['reorder_point']); ?></p>
            <p><strong>Date:</strong> <?php echo date('m/d/Y'); ?></p>
            <p><strong>History:</strong></p>

            <?php if (count($history_rows) > 0): ?>
                <div class="history-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Stock No.</th>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Unit</th>
                                <th>Reorder Point</th>
                                <th>Unit Cost (₱)</th>
                                <th>Quantity</th>
                                <th>Qty Change</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history_rows as $h): ?>
                                <tr>
                                    <td><?= date('M d, Y H:i', strtotime($h['changed_at'])) ?></td>
                                    <td><?= htmlspecialchars($h['stock_number']) ?></td>
                                    <td><?= htmlspecialchars($h['item_name']) ?></td>
                                    <td><?= htmlspecialchars($h['description']) ?></td>
                                    <td><?= htmlspecialchars($h['unit']) ?></td>
                                    <td><?= htmlspecialchars($h['reorder_point']) ?></td>
                                    <td><?= number_format($h['unit_cost'], 2) ?></td>
                                    <td><?= htmlspecialchars($h['quantity_on_hand']) ?></td>
                                    <td style="color: <?= $h['quantity_change'] > 0 ? 'green' : ($h['quantity_change'] < 0 ? 'red' : 'gray') ?>;">
                                        <?= $h['quantity_change'] > 0 ? '+' : '' ?><?= $h['quantity_change'] ?>
                                    </td>
                                    <td><?= ucfirst($h['change_type']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="font-style: italic; color: #888;">No history found for this item.</p>
            <?php endif; ?>

        </div>

    </div>
<script src="js/view_ris_script.js?v=<?= time() ?>"></script>
</body>
</html>