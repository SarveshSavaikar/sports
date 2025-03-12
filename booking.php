<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $ground_id = $_POST['ground_id'];
    $equipment_id = $_POST['equipment_id'];
    $booking_date = $_POST['booking_date'];

    $query = "INSERT INTO bookings (user_id, ground_id, equipment_id, booking_date) 
              VALUES ('$user_id', '$ground_id', '$equipment_id', '$booking_date')";

    if (mysqli_query($conn, $query)) {
        echo "Booking successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
