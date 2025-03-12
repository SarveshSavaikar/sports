<?php
// process_payment.php

// Include DB connection
include 'db_connection.php';

// Get payment data from the POST request
$data = json_decode(file_get_contents('php://input'), true);

// Extract payment data
$paymentData = $data['paymentData'];
$ground_id = $data['ground_id'];
$user_id = $_SESSION['user']['id']; // Assuming user is logged in

// In this example, we're assuming the payment was successful
$payment_status = 'successful'; 

// Mark the ground as booked in the database
$conn->query("UPDATE grounds SET availability = 0 WHERE id = $ground_id");

// Insert booking into the database
$conn->query("INSERT INTO bookings (user_id, ground_id, status) VALUES ($user_id, $ground_id, '$payment_status')");

// Send response back to the client
echo json_encode(['success' => true]);
?>
