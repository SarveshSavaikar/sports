<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in and is a customer
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

// Check if necessary data is provided
if (isset($_POST['equipment_id']) && isset($_POST['quantity'])) {
    $equipment_id = intval($_POST['equipment_id']);
    $quantity = intval($_POST['quantity']);
    $customer_id = $_SESSION['user']['id']; // Assuming user ID is stored in the session

    // Check if the equipment exists
    $sql = "SELECT * FROM equipment WHERE id = $equipment_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $available_quantity = $row['quantity'];

        // Check if enough stock is available
        if ($quantity <= $available_quantity) {
            // Insert the item into the cart table
            $insert_sql = "INSERT INTO cart (customer_id, equipment_id, quantity) VALUES ($customer_id, $equipment_id, $quantity)";
            $conn->query($insert_sql);

            // Redirect to the cart page or other page
            header("Location: cart.php");
            exit();
        } else {
            // Not enough stock
            $_SESSION['error'] = "Not enough stock available.";
            header("Location: buy_equipment.php");
            exit();
        }
    } else {
        // Equipment not found
        $_SESSION['error'] = "Equipment not found.";
        header("Location: buy_equipment.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: buy_equipment.php");
    exit();
}
?>
