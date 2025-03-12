<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    header('Location: login.php');
    exit();
}

include 'db_connection.php';

// Get the customer's ID from the session
$customer_id = $_SESSION['user']['id']; 

// Fetch only the logged-in customer's appointments
$query = "SELECT a.id, a.professional_id, a.customer_name, a.appointment_date, a.appointment_time, a.status, a.adminstatus ,
                 u.name AS professional_name, u.role AS professional_role
          FROM appointments a
          JOIN users u ON u.id = a.professional_id
          WHERE a.id = '$customer_id'";  // Corrected to filter by customer_id
          
$result = $conn->query($query);
if( $_SESSION['user']['role'] == 'admin'){
    $query = "SELECT a.id, a.professional_id, a.customer_name, a.appointment_date, a.appointment_time, a.status, a.adminstatus ,
                 u.name AS professional_name, u.role AS professional_role
          FROM appointments a
          JOIN users u ON u.id = a.professional_id
          WHERE a.adminstatus = 'pending'";  // Corrected to filter by customer_id
    $result = $conn->query($query);
}

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
            margin: 20px;
            padding: 0;
            background-color: #f8f9fa;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
            color: #333;
        }
        th {
            background-color:rgb(32, 78, 171);
            color: white;
        }
        .pending {
            background-color:rgb(237, 204, 16);
            color: white;
        }
        .rejected {
            background-color: #d9534f;
            color: white;
        }
        .confirmed {
            background-color:rgb(60, 231, 54);
            color: white;
        }
        .container {
            width: 80%;
            margin: auto;
        }
        .back-button {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px;
            background: #007bff;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .back-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
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
                <th>Admin Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($appointment = $result->fetch_assoc()): ?>
                <tr class="<?= strtolower($appointment['adminstatus']); ?>">  <!-- Add class based on status -->
                    <td><?= htmlspecialchars($appointment['professional_name']); ?></td>
                    <td><?= ucfirst(htmlspecialchars($appointment['professional_role'])); ?></td>
                    <td><?= htmlspecialchars($appointment['customer_name']); ?></td>
                    <td><?= htmlspecialchars($appointment['appointment_date']); ?></td>
                    <td><?= htmlspecialchars($appointment['appointment_time']); ?></td>
                    <td class = "<?= strtolower($appointment['status']); ?>" style = "color : black"><?= ucfirst($appointment['status']); ?></td>
                    <td class ="<?= strtolower($appointment['adminstatus']); ?>" style = "color : black" ><?= ucfirst($appointment['adminstatus']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="back-button">Go Back</a>
</div>

</body>
</html>
