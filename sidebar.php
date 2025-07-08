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
        <div class="logo">
            <img src="assets/images/tesda_logo.png" alt="TESDA Logo">
            <span>TESDA Inventory</span>
        </div>
            <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
            <nav>
                <a href="inventory.php" class="<?= $currentPage == 'inventory.php' ? 'active' : '' ?>">📋 Supply List</a>
                <a href="ris.php" class="<?= $currentPage == 'ris.php' ? 'active' : '' ?>">📑 RIS</a>
                <a href="rsmi.php" class="<?= $currentPage == 'rsmi.php' ? 'active' : '' ?>">🛡️ RSMI</a>
                <a href="#" class="<?= $currentPage == '#' ? 'active' : '' ?>">♻️ SC</a>
                <a href="#" class="<?= $currentPage == '#' ? 'active' : '' ?>">⚙️ RPCI</a>
            </nav>
    </div>

    <!-- Mobile Menu Toggle (for responsive design) -->
    <div class="mobile-menu-toggle">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <script>
        // Mobile menu toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.querySelector('.mobile-menu-toggle');
            const sidebar = document.querySelector('.sidebar');
            
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    this.classList.toggle('active');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 && 
                    !sidebar.contains(e.target) && 
                    !mobileToggle.contains(e.target) &&
                    sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    mobileToggle.classList.remove('active');
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('active');
                    mobileToggle.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>