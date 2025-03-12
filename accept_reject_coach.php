<?php
session_start();

// Check if user_id and role are set in session
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'coach') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

// Get the appointment ID from the URL
$appointment_id = $_GET['appointment_id'] ?? null; // Use null coalescing to handle undefined index

if (!$appointment_id) {
    echo "Appointment ID is missing.";
    exit();
}

// Fetch appointment details for the coach
$query = "SELECT a.id, a.customer_name, a.appointment_date, a.appointment_time, a.prize_charged, a.status
          FROM appointments a
          WHERE a.id = '$appointment_id' AND a.status = 'pending' AND a.professional_id = '$_SESSION[user_id]'"; 

$result = $conn->query($query);
$appointment = $result->fetch_assoc();

if (!$appointment) {
    echo "No pending appointment found.";
    exit();
}

// Handle accept/reject action
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    
    if ($action == 'accept') {
        $update_query = "UPDATE appointments SET status = 'confirmed' WHERE id = '$appointment_id'";
        $conn->query($update_query);
        echo "Appointment confirmed!";
    } else if ($action == 'reject') {
        $update_query = "UPDATE appointments SET status = 'rejected' WHERE id = '$appointment_id'";
        $conn->query($update_query);
        echo "Appointment rejected!";
    }
    // Redirect after action
    header('Location: professional_dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Action</title>
</head>
<body>

<h1>Appointment Details</h1>
<p><strong>Customer Name:</strong> <?= htmlspecialchars($appointment['customer_name']); ?></p>
<p><strong>Appointment Date:</strong> <?= htmlspecialchars($appointment['appointment_date']); ?></p>
<p><strong>Appointment Time:</strong> <?= htmlspecialchars($appointment['appointment_time']); ?></p>
<p><strong>Prize Charged:</strong> ₹<?= htmlspecialchars($appointment['prize_charged']); ?></p>

<h3>Actions</h3>
<form action="accept_reject_coach.php?appointment_id=<?= $appointment_id; ?>" method="POST">
    <button type="submit" name="action" value="accept">Accept</button>
    <button type="submit" name="action" value="reject">Reject</button>
</form>

</body>
</html>
