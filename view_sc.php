<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View SC - TESDA Inventory System</title>
    <link rel="stylesheet" href="css/styles.css?v=<?= time() ?>">
</head>
<body class="view-sc-page">
    <?php include 'sidebar.php'; ?>
    <?php require 'config.php'; ?>

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
    ?>

    <div class="content">
        <h2>📋 Viewing Item No. <?php echo htmlspecialchars($items['item_id']); ?></h2>

        <div class="SC-actions">
            <a href="SC.php" class="btn btn-secondary">← Back to SC List</a>
            <a >✏️ Edit SC</a>
            <a >📄 Export PDF</a>
        </div>

        <div class="SC-details">
            <p><strong>Entity Name:</strong> <?php echo "TESDA"; ?></p>
            <p><strong>Item:</strong> <?php echo htmlspecialchars($items['description']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($items['description']); ?></p>
            <p><strong>Stock No.:</strong> <?php echo htmlspecialchars($items['stock_number']); ?></p>
            <p><strong>Unit of Measurement:</strong> <?php echo htmlspecialchars($items['unit']); ?></p>
            <p><strong>Re-order Point:</strong> <?php echo htmlspecialchars($items['reorder_point']); ?></p>
            <p><strong>Date:</strong> <?php echo date('m/d/Y'); ?></p>
        </div>

    </div>
<script src="js/view_ris_script.js?v=<?= time() ?>"></script>
</body>
</html>