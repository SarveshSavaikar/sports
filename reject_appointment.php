<?php
include 'db_connection.php';

// Get the appointment ID from the URL
$appointment_id = $_GET['appointment_id'];

// Update the appointment status to 'rejected'
$update_query = "UPDATE appointments SET status = 'rejected' WHERE id = '$appointment_id'";
if ($conn->query($update_query) === TRUE) {
    echo "<script>alert('Appointment rejected.'); window.location.href = 'professional_dashboard.php';</script>";
} else {
    echo "<script>alert('Error rejecting appointment.'); window.location.href = 'professional_dashboard.php';</script>";
}
?>
