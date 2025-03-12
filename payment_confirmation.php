<?php
session_start();
include 'db_connection.php';

// Check if user is logged in and is a customer
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['user']['id']; // Assuming the user's ID is stored in the session
$order_id = $_SESSION['order_id']; // The order ID should be passed or stored in the session

// Update the order status to 'pending' after payment is confirmed
$sql = "UPDATE orders SET order_status = 'pending' WHERE id = $order_id AND customer_id = $customer_id";
if ($conn->query($sql) === TRUE) {
    $_SESSION['success'] = "Payment confirmed, awaiting admin approval.";
    header("Location: order_status.php"); // Redirect to the page where admin will approve/reject
    exit();
} else {
    $_SESSION['error'] = "Error updating order status: " . $conn->error;
    header("Location: payment_page.php");
    exit();
}
?>
