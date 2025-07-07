<?php include 'sidebar.php'; ?>

<div class="content">
    <h2>Requisition and Issue Slip (RIS)</h2>

    <button onclick="window.location.href='add_ris.php'">➕ Add RIS Form</button>

    <table>
        <thead>
            <tr>
                <th>RIS No.</th>
                <th>Date</th>
                <th>Requested By</th>
                <th>Purpose</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            require 'config.php';
            $result = $conn->query("SELECT * FROM ris");
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['ris_no']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['date_requested']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['requested_by']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['purpose']) . '</td>';
                    echo '<td>
                        <a href="view_ris.php?ris_id=' . $row["ris_id"] . '">View</a> |
                        <a href="edit_ris.php?ris_id=' . $row["ris_id"] . '">Edit</a> |
                        <a href="export_ris.php?ris_id=' . $row["ris_id"] . '">Export</a> |
                        <a href="delete_ris.php?ris_id=' . $row["ris_id"] . '" onclick="return confirm(\'Are you sure you want to delete this RIS?\')">Delete</a>
                    </td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5">No RIS records found.</td></tr>';
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
button {
    padding: 8px 16px;
    margin-bottom: 15px;
    cursor: pointer;
}
</style>
