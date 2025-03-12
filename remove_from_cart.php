<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in and is a customer
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

// Check if the cart item ID is passed in the request
if (isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];

    // Remove the item from the cart
    $sql = "DELETE FROM cart WHERE id = $cart_id AND customer_id = " . $_SESSION['user']['id'];
    
    if ($conn->query($sql) === TRUE) {
        // Item removed successfully, redirect to cart page
        header("Location: cart.php");
        exit();
    } else {
        // Error in deleting item
        echo "Error removing item: " . $conn->error;
    }
} else {
    // No cart ID passed, redirect to cart page
    header("Location: cart.php");
    exit();
}
?>
