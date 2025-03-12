<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Include DB connection
include 'db_connection.php';

// Get booking id and action from form submission
$booking_id = $_POST['booking_id'];
$action = $_POST['action'];

// Update booking status based on action
if ($action == 'accept') {
    $status = 'accepted';
} else if ($action == 'reject') {
    $status = 'rejected';
} else {
    header("Location: manage_bookings.php");
    exit();
}

// Update the booking status in the database
$query = "UPDATE bookings SET status = '$status' WHERE id = $booking_id";
$conn->query($query);

// Redirect back to the manage bookings page with a success message
header("Location: manage_bookings.php?status=success");
exit();
