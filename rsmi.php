<?php include 'sidebar.php'; ?>

<div class="content">
    <h2>Report on the Stock of Materials and Supplies Issued (RSMI)</h2>

    <table>
        <thead>
            <tr>
                <th>RIS No.</th>
                <th>Stock No.</th>
                <th>Item</th>
                <th>Unit</th>
                <th>Quantity Issued</th>
                <th>Unit Cost</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            require 'config.php';

            $result = $conn->query("
                SELECT ris.ris_no, ri.stock_number, i.description, i.unit, ri.issued_quantity, i.unit_cost,
                    (ri.issued_quantity * i.unit_cost) AS amount
                FROM ris_items ri
                JOIN ris ON ri.ris_id = ris.ris_id
                JOIN items i ON ri.stock_number = i.stock_number
                ORDER BY ris.date_requested DESC
            ");

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['ris_no']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['stock_number']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['unit']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['issued_quantity']) . '</td>';
                    echo '<td>₱ ' . number_format($row['unit_cost'], 2) . '</td>';
                    echo '<td>₱ ' . number_format($row['amount'], 2) . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="7">No RSMI entries found.</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <h3>Recapitulation</h3>
    <table>
        <thead>
            <tr>
                <th>Stock No.</th>
                <th>Total Quantity Issued</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $recap = $conn->query("
                SELECT stock_number, SUM(issued_quantity) AS total_issued
                FROM ris_items
                GROUP BY stock_number
            ");

            if ($recap && $recap->num_rows > 0) {
                while ($row = $recap->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['stock_number']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['total_issued']) . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="2">No recapitulation data found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
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
    padding: 10px;
    border: 1px solid #ddd;
}
</style>
