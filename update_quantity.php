<?php
session_start();
include 'db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cart_id']) && isset($_POST['action'])) {
    $cart_id = intval($_POST['cart_id']);
    $action = $_POST['action'];

    // Fetch the current quantity
    $sql = "SELECT quantity FROM cart WHERE id = $cart_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $quantity = $row['quantity'];

        // Adjust quantity based on action
        if ($action == "increase") {
            $quantity++;
        } elseif ($action == "decrease" && $quantity > 1) {
            $quantity--;
        }

        // Update the new quantity in the database
        $update_sql = "UPDATE cart SET quantity = $quantity WHERE id = $cart_id";
        $conn->query($update_sql);
    }
}

// Redirect back to cart page
header("Location: cart.php");
exit();
?>
