<?php 
require 'config.php';
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TESDA Inventory System</title>
    <link rel="stylesheet" href="css/styles.css?v=<?= time() ?>">
</head>
<body>
    <div class="sidebar">
        <a href="inventory.php" class="logo-text">
            <div class="logo">
                <img src="images/tesda_logo.png">
                <h3>Tesda Inventory</h3>
            </div>
        </a>

            <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
            <nav>
                <a href="inventory.php" class="<?= $currentPage == 'inventory.php' ? 'active' : '' ?>">📋 Supply List</a>
                <a href="ris.php" class="<?= in_array($currentPage, ['ris.php', 'add_ris.php', 'view_ris.php']) ? 'active' : '' ?>">📑 RIS</a>                <a href="rsmi.php" class="<?= $currentPage == 'rsmi.php' ? 'active' : '' ?>">🛡️ RSMI</a>
                <a href="SC.php" class="<?= in_array($currentPage, ['SC.php', "view_sc.php"]) ? 'active' : '' ?>">♻️ SC</a>
                <a href="rpci.php" class="<?= $currentPage == '"rpci.php"' ? 'active' : '' ?>">⚙️ RPCI</a>
            </nav>
    </div>

    <!-- Mobile Menu Toggle (for responsive design) -->
    <div class="mobile-menu-toggle">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <script src="js/sidebar_script.js?v=<?= time() ?>"></script>
</body>
</html>