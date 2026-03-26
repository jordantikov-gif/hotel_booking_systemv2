<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include("includes/db.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM reservations WHERE id = $id");
}

header("Location: dashboard.php");
exit;
?>
