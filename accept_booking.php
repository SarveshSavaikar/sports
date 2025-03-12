<?php
session_start();

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // Include DB connection
    include 'db_connection.php';

    // Update booking status to 'confirmed'
    $stmt = $conn->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to manage bookings page
    header("Location: manage_bookings.php");
    exit();
} else {
    echo "No booking ID provided.";
}
?>
