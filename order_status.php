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

// Fetch the order status
$sql = "SELECT order_status FROM orders WHERE id = $order_id AND customer_id = $customer_id";
$result = $conn->query($sql);
$order = $result->fetch_assoc();

if ($order) {
    echo "Your order status: " . htmlspecialchars($order['order_status']);
} else {
    echo "Order not found.";
}
?>
