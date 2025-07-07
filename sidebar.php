<div class="sidebar">
    <div class="logo">
        <img src="assets/images/tesda_logo.png" alt="TESDA Logo">
        <span>TESDA Inventory</span>
    </div>
    <nav>
        <a href="inventory.php">📋 Supply List</a>
        <a href="ris.php">📑 RIS</a>
        <a href="rsmi.php">🛡️ RSMI</a>
        <a href="#">♻️ SC</a>
        <a href="#">⚙️ RPCI</a>
    </nav>
</div>

<style>
body {
    margin-left: 240px;
    font-family: Arial, sans-serif;
}

.sidebar {
    width: 240px;
    background-color: rgb(199, 209, 219);
    color: black;
    height: 100vh;
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0;
    left: 0;
    box-shadow: 2px 0 6px rgba(0, 0, 0, 0.1);
}

.sidebar .logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.sidebar .logo img {
    width: 60px;
    margin-bottom: 8px;
}

.sidebar .logo span {
    font-size: 14px;
    font-weight: bold;
}

.sidebar nav {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    padding-top: 20px;
}

.sidebar nav a {
    padding: 12px 24px;
    color: black;
    text-decoration: none;
    font-size: 15px;
    transition: background 0.2s, padding-left 0.2s;
}

.sidebar nav a:hover {
    background-color: #014080;
    color: white;
    padding-left: 28px;
}
</style>
