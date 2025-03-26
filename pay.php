<?php
include 'db_connection.php';

// Get the appointment ID from the URL
$appointment_id = $_GET['appointment_id'];

// Fetch the appointment details using prepared statements
$stmt = $conn->prepare("SELECT a.customer_name, a.appointment_date, a.appointment_time, u.name as professional_name, u.id as professional_id, u.prize_charged
                        FROM appointments a
                        JOIN users u ON a.professional_id = u.id
                        WHERE a.appointment_id = ?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

// Handle payment confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['payment_done'])) {
    $payment_done = $_POST['payment_done'];
    $update_query = "";
    
    if ($payment_done == 'yes') {
        // Update the appointment status to 'pending' (awaiting professional's decision)
        $update_query = "UPDATE appointments SET status = 'pending' WHERE id = ?";
    } else {
        // Reject the appointment
        $update_query = "UPDATE appointments SET status = 'rejected' WHERE id = ?";
    }
    
    // Prepare and execute the update query
    $stmt_update = $conn->prepare($update_query);
    $stmt_update->bind_param("i", $appointment_id);
    
    if ($stmt_update->execute()) {
        echo "<script>alert('Booking Status is Updated!'); window.location.href = 'appointments.php';</script>";
    } else {
        echo "<script>alert('Error updating appointment.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            margin-top: 10px;
        }
        p {
            font-size: 20px;
        }
        label {
            font-weight: bold;
            font-size: 25px;
        }
        .main-container {
            max-width: 550px;
            height: 300px;
            margin-left:650px ;
            margin-top:250px ;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 15px;
            background-color: #f9f9f9;
            box-shadow: 0px 0px 20px 5px rgba(0, 0, 0, 0.5);
            
        }
        body {
            background-color:rgb(74, 139, 214);
        }
    </style>
</head>

<body>

<div class="main-container">
<h1>Booking Confirmation</h1>

<hr>


<p><strong>Customer Name:</strong> <?= htmlspecialchars($appointment['customer_name']); ?></p>
<p><strong>Professional:</strong> <?= htmlspecialchars($appointment['professional_name']); ?></p>
<p><strong>Appointment Date:</strong> <?= htmlspecialchars($appointment['appointment_date']); ?></p>
<p><strong>Appointment Time:</strong> <?= htmlspecialchars($appointment['appointment_time']); ?></p>
<!-- <p><strong>Prize Charged:</strong> ₹<?= htmlspecialchars($appointment['prize_charged']); ?></p> -->


<form action="<?= $_SERVER['PHP_SELF']; ?>?appointment_id=<?= $appointment_id; ?>" method="post">
    <label for="payment_done">Book Appointment</label>
    <select id="payment_done" name="payment_done" required>
        <option value="" disabled selected>Select to Confirm</option>
        <option value="yes">Yes</option>
        <option value="no">No</option>
    </select>

    <button type="submit">Submit</button>
</form>
</div>

</body>
</html>
