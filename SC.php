<?php include 'sidebar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RIS - TESDA Inventory System</title>
    <link rel="stylesheet" href="css/styles.css?v=<?= time() ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="SC-Page">

<div class="content">
    <h2> Stock Card (SC)</h2>

    
<div class="search-container-sc">
            <input type="text" id="searchInput" class="search-input-sc" placeholder="Search by stock number, description, or unit...">
</div>


    <table id="scTable">
        <thead>
            <tr>
                <th><i class=""></i> Stock No.</th>
                <th><i class=""></i> Item</th>
                <th><i class=""></i> Unit of Measurement</th>
                <th><i class=""></i> Reorder Point</th>
                <th><i class=""></i> Actions</th>
            </tr>
        </thead>
        <tbody>


        <!-- Search Bar JS-->
<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#scTable tbody tr');

        rows.forEach(row => {
            const stockNo = row.cells[0].textContent.toLowerCase();
            const description = row.cells[1].textContent.toLowerCase();
            const unit = row.cells[2].textContent.toLowerCase();

            const match = stockNo.includes(filter) || description.includes(filter) || unit.includes(filter);
            row.style.display = match ? '' : 'none';
        });
    });
</script>


            <?php 
            require 'config.php';
                $sql = "SELECT * FROM items ORDER BY stock_number ASC";
                $result = $conn->query($sql);            
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr data-id='{$row['item_id']}'>
                            <td><strong>{$row['stock_number']}</strong></td>
                            <td>{$row['description']}</td>
                            <td>{$row['unit']}</td>
                            <td>{$row['reorder_point']}</td>
                            <td>
                                <a href='view_sc.php?item_id={$row['item_id']}' title='View SC'>
                                    <i class='fas fa-eye'></i> View
                                </a>
                                <a class='scexport' href='export_sc.php?item_id={$row['item_id']}' title='Export SC'>
                                    <i class='fas fa-download'></i> Export
                                </a>
                            
                            </td>
                            
                        </tr>";
                }
            } else {
                echo '<tr><td colspan="5">
                        <i class="fas fa-inbox"></i> Item not found.
                      </td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>

