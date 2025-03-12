<?php
session_start();

// Check if user is logged in and has the 'admin' role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

// Check if equipment ID is passed
if (isset($_GET['id'])) {
    $equipment_id = $_GET['id'];

    // Delete equipment from the database
    $query = "DELETE FROM equipment WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $equipment_id);
    $stmt->execute();

    // Redirect after deletion
    header("Location: manage_equipment.php");
} else {
    echo "No equipment ID provided!";
    exit();
}
?>
