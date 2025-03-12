<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $appointment_date = $_POST['appointment_date'];
    $time_slot = $_POST['time_slot'];

    $query = "INSERT INTO appointments (user_id, appointment_date, time_slot) 
              VALUES ('$user_id', '$appointment_date', '$time_slot')";

    if (mysqli_query($conn, $query)) {
        echo "Appointment booked successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
