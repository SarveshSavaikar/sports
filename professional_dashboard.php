<?php
session_start();

// Check if user_id is set in session
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    header('Location: login.php');
    exit();
}

include 'db_connection.php';

// Get the professional's ID from the session
$professional_id = $_SESSION['user']['id'];

// Fetch the pending, confirmed, or rejected appointments for the logged-in professional
$query = "SELECT a.appointment_id ,a.id, a.customer_name, a.appointment_date, a.appointment_time, u.prize_charged, a.status , a.adminstatus
          FROM appointments a
          JOIN users u ON u.id = a.professional_id
          WHERE a.professional_id = '$professional_id' AND (a.status = 'pending' OR a.status = 'confirmed' OR a.status = 'rejected')";
$result = $conn->query($query);
// echo "re-ran the query";

if($_SESSION['user']['role'] == 'admin'){
    $query = "SELECT a.id, a.customer_name, a.appointment_date, a.appointment_time, u.prize_charged, a.status , a.adminstatus
          FROM appointments a
          JOIN users u ON u.id = a.professional_id
          WHERE a.adminstatus = 'pending' and a.status = 'confirmed'";  // Corrected to filter by customer_id
          $result = $conn->query($query);
}

if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Handle accept/reject/reverse action
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $appointment_id = $_POST['appointment_id'];

    if ($action == 'accept') {
        $update_query = "UPDATE appointments SET status = 'confirmed' WHERE appointment_id = '$appointment_id'";
        $conn->query($update_query);
        echo "Appointment confirmed!";
        echo $appointment_id;
    } elseif ($action == 'reject') {
        $update_query = "UPDATE appointments SET status = 'rejected' WHERE appointment_id = '$appointment_id'";
        $conn->query($update_query);
        echo $appointment_id;
        echo "Appointment rejected!";
    } elseif ($action == 'reverse') {
        $update_query = "UPDATE appointments SET status = 'pending' WHERE appointment_id = '$appointment_id'";
        $conn->query($update_query);
        echo $appointment_id;
        echo "Appointment status reversed to pending!";
    }

    // Redirect to avoid resubmission of form
    header("Location: professional_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Dashboard</title>
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
        a {
            padding: 5px 10px;
            margin: 5px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a.reject {
            background-color: #dc3545;
        }
        a.reverse {
            background-color: #ffc107;
            color: black;
        }
    </style>
</head>
<!-- Back Button -->
<button onclick="history.back()">Go Back</button>
<body>

<h1>Pending and Confirmed Appointments</h1>

<table style="padding-left: 100px; padding-right: 30px;">
    <thead>
        <tr>
            <th>Customer Name</th>
            <th>Appointment Date</th>
            <th>Appointment Time</th>
            <th>Prize Charged</th>
            <th>Status</th>
            <th>Action</th>
            <th>Admin Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($appointment = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($appointment['customer_name']); ?></td>
                <td><?= htmlspecialchars($appointment['appointment_date']); ?></td>
                <td><?= htmlspecialchars($appointment['appointment_time']); ?></td>
                <td>₹<?= htmlspecialchars($appointment['prize_charged']); ?></td>
                <td><?= ucfirst($appointment['status']); ?></td>
                <td>
                    <?php if ($appointment['status'] == 'pending'): ?>
                        <form action="professional_dashboard.php" method="POST" style="display:inline;">
                            <button type="submit" name="action" value="accept">Accept</button>
                            <button type="submit" name="action" value="reject" class="reject">Reject</button>
                            <input type="hidden" name="appointment_id" value="<?= $appointment['appointment_id']; ?>">
                        </form>
                    <?php elseif ($appointment['status'] == 'confirmed' || $appointment['status'] == 'rejected'): ?>
                        <form action="professional_dashboard.php" method="POST" style="display:inline;">
                            <button type="submit" name="action" value="reverse" class="reverse">Reverse</button>
                            <input type="hidden" name="appointment_id" value="<?= $appointment['appointment_id']; ?>">
                        </form>
                    <?php endif; ?>
                </td>
                <td><?= ucfirst($appointment['adminstatus']); ?></td>

            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
