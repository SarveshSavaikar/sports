<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    header('Location: login.php');
    exit();
}

include 'db_connection.php';

// Get the customer's ID from the session
$customer_id = $_SESSION['user']['id'];  // Correct variable for logged-in customer

// Fetch all appointments for the logged-in customer including pending, rejected, and confirmed statuses
$query = "SELECT a.id, a.professional_id, a.customer_name, a.appointment_date, a.appointment_time, a.status, 
                 u.name AS professional_name, u.role AS professional_role
          FROM appointments a
          JOIN users u ON u.id = a.professional_id
          WHERE a.id = '$customer_id'";  // Ensure using $customer_id
$result = $conn->query($query);

if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .pending {
            background-color: #f0ad4e;
        }
        .rejected {
            background-color: #d9534f;
        }
        .confirmed {
            background-color: #5bc0de;
        }
    </style>
</head><!-- Back Button -->
<button onclick="history.back()">Go Back</button>
<body>

<h1>Your Appointments</h1>

<table>
    <thead>
        <tr>
            <th>Professional Name</th>
            <th>Role</th>
            <th>Customer Name</th>
            <th>Appointment Date</th>
            <th>Appointment Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($appointment = $result->fetch_assoc()): ?>
            <tr class="<?= strtolower($appointment['status']); ?>">  <!-- Add class based on status -->
                <td><?= htmlspecialchars($appointment['professional_name']); ?></td>
                <td><?= ucfirst(htmlspecialchars($appointment['professional_role'])); ?></td>
                <td><?= htmlspecialchars($appointment['customer_name']); ?></td>
                <td><?= htmlspecialchars($appointment['appointment_date']); ?></td>
                <td><?= htmlspecialchars($appointment['appointment_time']); ?></td>
                <td><?= ucfirst($appointment['status']); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
