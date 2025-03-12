<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

// Check if the order_id and action are passed in the URL
if (isset($_GET['order_id']) && isset($_GET['action'])) {
    $order_id = intval($_GET['order_id']);
    $action = $_GET['action'];

    // Validate action type
    if (in_array($action, ['accept', 'reject', 'pending'])) {
        // Determine the new status based on the action
        switch ($action) {
    case 'accept':
        $new_status = 'accepted';
        break;
    case 'reject':
        $new_status = 'rejected';
        break;
    default:
        $new_status = 'pending';
        break;
}
        // Update the order status in the database
     $sql = "UPDATE orders SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
    $_SESSION['error'] = "Error preparing the statement: " . $conn->error;
    exit();
}

$stmt->bind_param("si", $new_status, $order_id);

        if ($stmt->execute()) {
            // If accepting the order, update quantities in the equipment table
            if ($action == 'accept') {
                $update_qty_sql = "UPDATE equipment e
                    JOIN order_items oi ON e.id = oi.equipment_id
                    SET e.quantity = e.quantity - oi.quantity_ordered
                    WHERE oi.order_id = ?";
                $update_stmt = $conn->prepare($update_qty_sql);
                $update_stmt->bind_param("i", $order_id);
                if (!$update_stmt->execute()) {
                    $_SESSION['error'] = "Error updating equipment quantities on accept.";
                }
            }

            // If rejecting the order, reverse quantities in the order_items table
            if ($action == 'reject') {
                // Ensure the quantities are added back to the equipment table correctly
                $reverse_qty_sql = "UPDATE equipment e
                    JOIN order_items oi ON e.id = oi.equipment_id
                    SET e.quantity = e.quantity + oi.quantity_ordered
                    WHERE oi.order_id = ?";
                $reverse_stmt = $conn->prepare($reverse_qty_sql);
                $reverse_stmt->bind_param("i", $order_id);
                if (!$reverse_stmt->execute()) {
                    $_SESSION['error'] = "Error updating equipment quantities on reject.";
                }
            }

            $_SESSION['success'] = "Order has been successfully updated to $new_status.";
        } else {
            $_SESSION['error'] = "Error updating the order status.";
        }
    } else {
        $_SESSION['error'] = "Invalid action.";
    }
} else {
    $_SESSION['error'] = "Order ID or action is missing.";
}

// Redirect back to the manage orders page
header("Location: manage_orders.php");
exit();
?>
