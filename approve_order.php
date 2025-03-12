<?php
session_start();

// Check if user is logged in and has the 'admin' role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Update the order status to 'approved'
    $sql = "UPDATE orders SET order_status = 'approved' WHERE id = $order_id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Order has been approved.";
        header("Location: admin_manage_orders.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating order: " . $conn->error;
        header("Location: admin_manage_orders.php");
        exit();
    }
}
?>
