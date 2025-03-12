<?php
include 'db_connection.php';

// Get the appointment ID from the URL
$appointment_id = $_GET['appointment_id'];

// Update the appointment status to 'confirmed'
$update_query = "UPDATE appointments SET status = 'confirmed' WHERE id = '$appointment_id'";
if ($conn->query($update_query) === TRUE) {
    echo "<script>alert('Appointment confirmed!'); window.location.href = 'professional_dashboard.php';</script>";
} else {
    echo "<script>alert('Error confirming appointment.'); window.location.href = 'professional_dashboard.php';</script>";
}
?>
