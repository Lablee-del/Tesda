<?php
require 'config.php';
require 'functions.php';

// DELETE LOGIC (moved from delete_ris.php)
if (isset($_GET['delete_ris_id'])) {
    $ris_id = (int)$_GET['delete_ris_id'];
    // Delete RIS items first due to foreign key constraint
    $conn->query("DELETE FROM ris_items WHERE ris_id = $ris_id");
    // Then delete RIS header
    $conn->query("DELETE FROM ris WHERE ris_id = $ris_id");
    // Redirect to avoid resubmission on refresh
    header("Location: ris.php");
    exit();
}

// SORT LOGIC
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'date_newest';
$order_clause = '';

switch ($sort_by) {
    case 'ris_no':
        $order_clause = "ORDER BY ris_no ASC";
        break;
    case 'date_oldest':
        $order_clause = "ORDER BY date_requested ASC";
        break;
    case 'date_newest':
    default:
        $order_clause = "ORDER BY date_requested DESC";
        break;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RIS - TESDA Inventory System</title>
    <link rel="stylesheet" href="css/styles.css?v=<?= time() ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
</head>
<body class="ris-page">
<?php include 'sidebar.php'; ?>
<div class="content">
    <h2>Requisition and Issue Slip (RIS)</h2>
    
    <div class="header-controls">
        <button onclick="window.location.href='add_ris.php'">
            <i class="fas fa-plus"></i> Add RIS Form
        </button>
        
        <div class="sort-container">
            <label for="sort-select">
                <i class="fas fa-sort"></i> Sort by:
            </label>
            <select id="sort-select" onchange="sortTable(this.value)">
                <option value="date_newest" <?= ($sort_by == 'date_newest') ? 'selected' : '' ?>>Date (Newest First)</option>
                <option value="date_oldest" <?= ($sort_by == 'date_oldest') ? 'selected' : '' ?>>Date (Oldest First)</option>
                <option value="ris_no" <?= ($sort_by == 'ris_no') ? 'selected' : '' ?>>RIS No. (A-Z)</option>
            </select>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th><i class="fas fa-hashtag"></i> RIS No.</th>
                <th><i class="fas fa-calendar"></i> Date</th>
                <th><i class="fas fa-user"></i> Requested By</th>
                <th><i class="fas fa-clipboard-list"></i> Purpose</th>
                <th><i class="fas fa-cogs"></i> Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $result = $conn->query("SELECT * FROM ris $order_clause");
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td><strong>' . htmlspecialchars($row['ris_no']) . '</strong></td>';
                    echo '<td>' . date('M d, Y', strtotime($row['date_requested'])) . '</td>';
                    echo '<td>' . htmlspecialchars($row['requested_by']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['purpose']) . '</td>';
                    echo '<td>
                        <a href="view_ris.php?ris_id=' . $row["ris_id"] . '" title="View RIS">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="add_ris.php?ris_id=' . $row["ris_id"] . '" title="Edit RIS">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="export_ris.php?ris_id=' . $row["ris_id"] . '" title="Export RIS">
                            <i class="fas fa-download"></i> Export
                        </a>
                        <a href="ris.php?delete_ris_id=' . $row["ris_id"] . '" 
                           onclick="return confirm(\'Are you sure you want to delete this RIS?\')"
                           title="Delete RIS">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5">
                        <i class="fas fa-inbox"></i> No RIS records found.
                      </td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<script>
function sortTable(sortBy) {
    // Get current URL and update sort parameter
    const url = new URL(window.location);
    url.searchParams.set('sort', sortBy);
    
    // Redirect to new URL with sort parameter
    window.location.href = url.toString();
}
</script>

</body>
</html>