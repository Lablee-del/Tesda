<?php
require 'config.php';

if (isset($_GET['ris_id'])) {
    $ris_id = (int)$_GET['ris_id'];

    // Delete RIS items first (due to foreign key constraint)
    $conn->query("DELETE FROM ris_items WHERE ris_id = $ris_id");

    // Then delete RIS header
    $conn->query("DELETE FROM ris WHERE ris_id = $ris_id");
}

header("Location: ris.php");
exit();
?>
